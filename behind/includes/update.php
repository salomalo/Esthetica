<?php
$javascript = '';

if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validation = new Validation();

        $validation->check($_POST, array(
            'inputGender' => array(
                'required' => true,
                'fieldName' => 'Sexe',
                'group' => 'genderGroup'
            ),
            'inputFirstName' => array(
                'required' => true,
                'fieldName' => 'Prénom',
                'group' => 'firstNameGroup',
                'min' => 2,
                'max' => 20
            ),
            'inputLastName' => array(
                'required' => true,
                'fieldName' => 'Nom de famille',
                'group' => 'lastNameGroup',
                'min' => 2,
                'max' => 80
            ),
            'inputEmail' => array(
                'regex' => '/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',
                'fieldName' => 'Courriel',
                'group' => 'emailGroup',
            ),
            'inputPassword' => array(
                'fieldName' => 'Mot de passe',
                'group' => 'passwordGroup',
                'min' => 5,
                'max' => 100,
            ),
            'inputPasswordAgain' => array(
                'fieldName' => 'Mot de passe (encore)',
                'group' => 'passwordAgainGroup',
                'matches' => 'inputPassword'
            )
        ));

        if ($validation->passed()) {
            $password = Input::get('inputPassword');
            $passwordAgain = Input::get('inputPasswordAgain');
            if (isset($password)) {
                if (isset($passwordAgain) && $password === $passwordAgain) {
                    $salt = Hash::salt(32);
                    $fields['password'] = Hash::make($password . $salt);
                    $fields['salt'] = $salt;
                }
            }
            $fields['gender'] = Input::get('inputGender');
            $fields['firstname'] = Input::get('inputFirstName');
            $fields['lastname'] = Input::get('inputLastName');
            $fields['email'] = Input::get('inputEmail');

            $user->update($fields);
            Redirect::to('update');
        } else {
            $errors = '<div class="alert alert-danger"><strong>Oups!</strong> Veuillez corriger les problèmes suivants:<br /><br />';
            foreach ($validation->errors() as $key) {
                $errors .= $key['message'] . '<br />';
                $javascript .= '$(\'#' . $key['group'] . '\').addClass(\'has-error\').delay(1000).effect("bounce"); ';
            }
            $errors .= '</div>';
        }
    } else {
        Redirect::to('update');
    }
}

$token = Token::generate();

$male = '';
$female = '';
// selected for the gender

if ($user->data()->gender == 1) {
    $male = ' checked';
} else if ($user->data()->gender == 2) {
    $female = ' checked';
}
?>
<script type="text/javascript">
    $(document).ready(function (e) {
        $(document).attr('title', 'Paramètres - Esthética');
    });
</script>
<div class="col-md-12">
    <h1>Mon compte <small>Gestion de clientèle</small></h1>
    <div class="list-group col-md-3">
        <?php
        include 'back-sidebar.php';
        ?>
    </div>

    <div class="tab-content col-md-9">
        <div class="tab-pane active" id="home">
            <h3>Mes paramètres</h3>
            <?php
            echo (empty($errors)) ? '' : $errors;
            ?>
            <form class="form-horizontal" role="form" action="index.php?action=update" method="post">
                <div id="phoneNumberGroup" class="form-group">
                    <label for="inputPhoneNumber" class="col-md-3 col-md-offset-1 control-label">Numéro de téléphone:</label>
                    <div class="col-md-8">
                        <p class="form-control-static"><?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $user->data()->phone)); ?>
                            <span class="help-block">Vous devez <a href="index.php?action=contacteznous">nous contacter</a> pour changer ce numéro.</span></p>
                    </div>
                </div>
                <?php
                if (!$user->data()->facebook_id) {
                    ?>
                    <div id="genderGroup" class="form-group">
                        <label for="inputGender" class="col-md-3 col-md-offset-1 control-label">Sexe:</label>
                        <div class="col-md-8">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="inputGender" id="maleRadio" value="1"<?php echo $male; ?>>
                                    Homme <i class="fa fa-male fa-fw"></i>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="inputGender" id="femaleRadio" value="2"<?php echo $female; ?>>
                                    Femme <i class="fa fa-female fa-fw"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="firstNameGroup" class="form-group">
                        <label for="inputFirstName" class="col-md-3 col-md-offset-1 control-label">Prénom:</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="inputFirstName" name="inputFirstName" placeholder="Votre prénom" value="<?php echo escape($user->data()->firstName); ?>">
                        </div>
                    </div>
                    <div id="lastNameGroup" class="form-group">
                        <label for="inputFirstName" class="col-md-3 col-md-offset-1 control-label">Nom de famille:</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="inputLastName" name="inputLastName" placeholder="Votre nom de famille" value="<?php echo escape($user->data()->lastName); ?>">
                        </div>
                    </div>
                    <div id="emailGroup" class="form-group">
                        <label for="inputEmail" class="col-md-3 col-md-offset-1 control-label">Courriel:</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Votre adresse courriel" value="<?php echo escape($user->data()->email); ?>">
                            <span class="help-block">(facultatif)</span>
                        </div>
                    </div>
                    <div id="userNameGroup" class="form-group">
                        <label for="inputUserName" class="col-md-3 col-md-offset-1 control-label">Nom d'utilisateur:</label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?php echo escape($user->data()->username); ?>
                                <span class="help-block">Vous devez <a href="index.php?action=contacteznous">nous contacter</a> pour changer ceci.</span></p>
                        </div>
                    </div>
                    <div id="passwordGroup" class="form-group">
                        <label for="inputPassword" class="col-md-3 col-md-offset-1 control-label">Nouveau mot de passe:</label>
                        <div class="col-md-4">
                            <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Mot de passe" value="">
                        </div>
                    </div>
                    <div id="passwordAgainGroup" class="form-group">
                        <label for="inputPasswordAgain" class="col-md-3 col-md-offset-1 control-label">Nouveau mot de passe (encore):</label>
                        <div class="col-md-4">
                            <input type="password" class="form-control" id="inputPasswordAgain" name="inputPasswordAgain" placeholder="Mot de passe (encore)" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Sauvegarder</button>
                        </div>
                    </div>
                    <input type="hidden" name="token" value="<?php echo $token; ?>" />
                    <?php
                } else {
                    ?>
                    <div id="userGroup" class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <span class="help-block"><strong>Votre compte est synchronisé via Facebook.</strong> En changeant vos informations là-bas, vous pouvez resynchroniser vos informations à l'aide du bouton ci-dessous.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <a href="index.php?action=facebookLogin&fromUpdate=1" class="btn btn-primary"><span class="fa fa-cloud-download"></span> Resynchroniser</a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
<?php echo $javascript; ?>
    });
</script>