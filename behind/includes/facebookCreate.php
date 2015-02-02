<?php
require_once('behind/classes/facebook/facebook.php');

if ($user->isLoggedIn()) {
    Redirect::to('accueil');
}

$javascript = '';
$config = array(
    'appId' => '1388507124730191',
    'secret' => '115ad244dcc4d602ee6205d2cce175f7',
    'allowSignedRequest' => false // optional but should be set to false for non-canvas apps
);
$facebook = new Facebook($config);
if (isset($_GET['destroy'])) {
    $facebook->destroySession();
    Redirect::to('facebookCreate');
}
if (!Input::exists()) {
    $user_id = $facebook->getUser();

    if ($user_id) {
        // We have a user ID, so probably a logged in user.
        // If not, we'll get an exception, which we handle below.
        try {
            $image = $facebook->api('/me/picture', 'GET');
            $user_profile = $facebook->api('/me?fields=first_name,last_name,email,gender', 'GET');
            Session::put('facebook_id', $user_id);
            Session::put('first_name', $user_profile['first_name']);
            Session::put('last_name', $user_profile['last_name']);
            Session::put('email', @$user_profile['email']);
            if ($user_profile['gender'] === 'male') {
                $gender = 1;
            } else if ($user_profile['gender'] === 'female') {
                $gender = 2;
            }
            Session::put('gender', $gender);

            // if user facebook id exists redirect
            $check = new User();
            if ($check->findFacebook($user_id)) {
                Session::put('exists', true);
            } else {
                Session::put('exists', false);
            }
        } catch (FacebookApiException $e) {
            // If the user is logged out, you can have a
            // user ID even though the access token is invalid.
            // In this case, we'll get an exception, so we'll
            // just ask the user to login again here.
            $login_url = $facebook->getLoginUrl(array('scope' => 'email'));
            Session::put('exists', false);
        }
    } else {
        // No user, print a link for the user to login
        $login_url = $facebook->getLoginUrl(array('scope' => 'email'));
        Session::put('exists', false);
    }
} else {
    if (Token::check(Input::get('token'))) {
        if (Session::get('exists') == false) {
            $validation = new Validation();

            $validation->check($_POST, array(
                'inputPhoneNumber' => array(
                    'required' => true,
                    'regex' => "/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/",
                    'fieldName' => 'Numéro de téléphone',
                    'unique' => 'users',
                    'fieldUnique' => 'phone',
                    'group' => 'phoneNumberGroup'
                )
            ));

            if ($validation->passed()) {
                // Validation passed - up to second step now.
                Session::put('phone', Input::get('inputPhoneNumber'));
                $user = new User(-1);
                try {
                    $newUser = $user->create(array(
                        /* not required. */
                        'username' => 'facebook_' . Session::get('facebook_id'),
                        'password' => '',
                        'salt' => '',
                        'phone' => preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", Session::get('phone')),
                        'gender' => Session::get('gender'),
                        'firstName' => Session::get('first_name'),
                        'lastName' => Session::get('last_name'),
                        'email' => Session::get('email'),
                        'joined' => date('Y-m-d H:i:s'),
                        'facebook_id' => Session::get('facebook_id'),
                        'clientGroup' => 1
                    ));

                    $user = new User(-1);
                    $user->findFacebook(Session::get('facebook_id'));
                    $user->login();

                    Redirect::to('facebookCreate2&token=' . Token::generate());
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $errors = '<div class="alert alert-danger"><strong>Oups!</strong> Veuillez corriger les problèmes suivants:<br />';
                foreach ($validation->errors() as $key) {
                    $errors .= $key['message'] . '<br />';
                    $javascript .= '$(\'#' . $key['group'] . '\').addClass(\'has-error\').delay(500).effect("bounce"); ';
                }
                $errors .= '</div>';
            }
        }
    } else {
        $errors = '<div class="alert alert-danger"><strong>Oups!</strong> Assurez-vous de soumettre le formulaire avec le bouton Continuer.</div>';
    }
}
?>

<script type="text/javascript">
    $(document).ready(function (e) {
        $(document).attr('title', 'Inscription via Facebook - Esthética');
    });
</script>
<div class="row">
    <div class="col-lg-offset-1 col-lg-10">
        <h1>Inscription via Facebook</h1>
        <?php echo (empty($errors)) ? '' : $errors; ?>
        <p>La création de votre compte est presque terminée! Veuillez entrer votre numéro de téléphone pour continuer. Il devriendra alors votre numéro de référence pour votre compte. Lors de vos achats, nous vous demanderons votre numéro de téléphone pour obtenir les informations de votre compte.</p>
        <h3>Informations</h3>
        <form class="form-horizontal" role="form" action="index.php?action=facebookCreate" method="post">
            <div id="changeUserGroup" class="form-group">
                <div class="col-lg-6 col-lg-offset-4">
                    <?php if (isset($login_url)) { ?>
                        <a class="btn btn-facebook" href="<?php echo $login_url; ?>"><i class="fa fa-facebook"></i> | Connexion</a>
                    <?php } else { ?>
                        <img src="https://graph.facebook.com/<?php echo Session::get('facebook_id'); ?>/picture" class="img-rounded">&nbsp;
                        <a class="btn btn-facebook" href="<?php echo $facebook->getLogoutUrl(array('next' => 'http://onglestrycia.godevz.com/index.php?action=facebookCreate&destroy=1')); ?>"><i class="fa fa-facebook"></i> | Déconnexion</a>
                    <?php } ?>
                </div>
            </div>
            <?php
            if (!isset($login_url)) {
                if (Session::get('exists') == false) {
                    ?>
                    <div id="phoneNumberGroup" class="form-group">
                        <label for="inputPhoneNumber" class="col-lg-3 col-lg-offset-1 control-label">Numéro de téléphone</label>
                        <div class="col-lg-3">
                            <input type="tel" class="form-control" id="inputPhoneNumber" name="inputPhoneNumber" placeholder="(xxx) xxx-xxxx" value="<?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", Input::get('inputPhoneNumber'))); ?>">
                        </div>
                    </div>
                    <div id="genderGroup" class="form-group">
                        <label for="inputGender" class="col-lg-3 col-lg-offset-1 control-label">Sexe</label>
                        <div class="col-lg-3">
                            <p class="form-control-static"><?php echo (Session::get('gender') == 1 ? 'Homme <i class="fa fa-male"></i>' : 'Femme <i class="fa fa-female"></i>'); ?></p>
                        </div>
                    </div>
                    <div id="firstNameGroup" class="form-group">
                        <label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Prénom</label>
                        <div class="col-lg-3">
                            <p class="form-control-static"><?php echo escape(Session::get('first_name')); ?></p>
                        </div>
                    </div>
                    <div id="lastNameGroup" class="form-group">
                        <label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Nom de famille</label>
                        <div class="col-lg-4">
                            <p class="form-control-static"><?php echo escape(Session::get('last_name')); ?></p>
                        </div>
                    </div>
                    <div id="emailGroup" class="form-group">
                        <label for="inputEmail" class="col-lg-3 col-lg-offset-1 control-label">Courriel</label>
                        <div class="col-lg-4">
                            <p class="form-control-static"><?php echo escape(Session::get('email')); ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-3">
                            <button type="submit" class="btn btn-primary">Continuer &raquo;</button> &nbsp;
                            <a href="index.php?action=login">Annuler</a>
                        </div>
                    </div>
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <?php
                }
            }
            ?>
            <?php if (Session::get('exists') == true) { ?>
                <div id="existsGroup" class="form-group">
                    <div class="col-lg-6 col-lg-offset-4">
                        <div class="alert alert-info">Ce compte Facebook existe déjà! <a href="index.php?action=facebookLogin">Cliquez ici pour vous connecter.</a></div>
                    </div>
                </div>
            <?php } ?>
        </form>
    </div>
    <script>
        $(this).ready(function () {
<?php echo $javascript; ?>
        });
    </script>
</div>