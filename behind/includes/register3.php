<?php 
// STEP 3
// Identification info

// Phone number for DB: preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", $input_lines);
// Phone number for display: preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $input_lines);	
	
$javascript = '';

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validation = new Validation();
		
		$validation->check($_POST, array(
			'inputUserName' => array(
				'required' => true,
				'fieldName' => 'Nom d\'utilisateur',
				'group' => 'userNameGroup',
				'min' => 2,
				'max' => 30,
				'unique' => 'users',
				'fieldUnique' => 'username'
			),
			'inputPassword' => array(
				'required' => true,
				'fieldName' => 'Mot de passe',
				'group' => 'passwordGroup',
				'min' => 5,
				'max' => 100,
			),
			'inputPasswordAgain' => array(
				'required' => true,
				'fieldName' => 'Mot de passe (encore)',
				'group' => 'passwordAgainGroup',
				'matches' => 'inputPassword'
			)
		));
		
		if($validation->passed()) {			
			Session::put('inputUserName', Input::get('inputUserName'));
			Session::put('inputPassword', Input::get('inputPassword'));
			
			Redirect::to('register4&token=' . Token::generate());
		}
		else {
			$errors = '<div class="alert alert-danger"><strong>Oups!</strong> Veuillez corriger les problèmes suivants:<br /><br />';
			foreach($validation->errors() as $key) {
				$errors .= $key['message'] . '<br />';
				$javascript .= '$(\'#' . $key['group'] . '\').addClass(\'has-error\').delay(1000).effect("bounce"); ';
			}
			$errors .= '</div>';
		}
	}
	else {
		Redirect::to('register');
	}
}
else {
	if(!Token::check(Input::get('token'))) {
		Redirect::to('register');
	}
}

$token = Token::generate();

?>
		<script type="text/javascript">
		$(document).ready(function(e) {
			$(document).attr('title', 'Inscription - Pose d\'ongles Trycia');
		});
		</script>
        <div class="row">
        	<div class="col-lg-offset-1 col-lg-10">            
            	<h1>Inscription <small>Troisième étape</small></h1>
				<ol class="breadcrumb">
					<li><a href="index.php?action=register&inputPhoneNumber=<?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", Session::get('inputPhoneNumber'))); ?>&token=<?php echo $token; ?>">Étape 1</a></li>
					<li><a href="index.php?action=register2&inputFirstName=<?php echo escape(Session::get('inputFirstName')); ?>&inputLastName=<?php echo escape(Session::get('inputLastName')); ?>&inputEmail=<?php echo escape(Session::get('inputEmail')); ?>&token=<?php echo $token; ?>">Étape 2</a></li>
					<li class="active">Étape 3</li>
					<li>Étape 4</li>
				</ol>
				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-default" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
						<span class="sr-only">75% complété</span>
					</div>
				</div>
				<?php echo (empty($errors)) ? '' : $errors; ?>
                <p>Avec toutes les informations que nous avons à présent, votre compte personnel est terminé. Pour compléter la création de votre compte, vous devez choisir un nom d'utilisateur personnel ainsi qu'un mot de passe.</p>
				<h3>Informations</h3>
            	<form class="form-horizontal" role="form" action="index.php?action=register3" method="post">
                    <div id="phoneNumberGroup" class="form-group">
                    <label for="inputPhoneNumber" class="col-lg-3 col-lg-offset-1 control-label">Numéro de téléphone</label>
                        <div class="col-lg-3">
                            <p class="form-control-static"><?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", Session::get('inputPhoneNumber'))); ?> <a class="small" href="index.php?action=register&inputPhoneNumber=<?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", Session::get('inputPhoneNumber'))); ?>&token=<?php echo $token; ?>">modifier</a></p>
                        </div>
                    </div>
					<div id="firstNameGroup" class="form-group">
                    <label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Prénom</label>
                        <div class="col-lg-3">
							<p class="form-control-static"><?php echo escape(Session::get('inputFirstName')); ?> <a class="small" href="index.php?action=register2&inputFirstName=<?php echo escape(Session::get('inputFirstName')); ?>&inputLastName=<?php echo escape(Session::get('inputLastName')); ?>&inputEmail=<?php echo escape(Session::get('inputEmail')); ?>&inputGender=<?php echo escape(Session::get('inputGender')); ?>&token=<?php echo $token; ?>">modifier</a></p>
                        </div>
                    </div>	
					<div id="genderGroup" class="form-group">
                    <label for="inputGender" class="col-lg-3 col-lg-offset-1 control-label">Sexe</label>
                        <div class="col-lg-3">
							<p class="form-control-static"><?php echo (Session::get('inputGender') == 1 ? 'Homme <i class="fa fa-male fa-fw"></i>' : 'Femme <i class="fa fa-female fa-fw"></i>'); ?> <a class="small" href="index.php?action=register2&inputFirstName=<?php echo escape(Session::get('inputFirstName')); ?>&inputLastName=<?php echo escape(Session::get('inputLastName')); ?>&inputEmail=<?php echo escape(Session::get('inputEmail')); ?>&inputGender=<?php echo escape(Session::get('inputGender')); ?>&token=<?php echo $token; ?>">modifier</a></p>
                        </div>
                    </div>
					<div id="firstNameGroup" class="form-group">
                    <label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Prénom</label>
                        <div class="col-lg-3">
							<p class="form-control-static"><?php echo escape(Session::get('inputFirstName')); ?> <a class="small" href="index.php?action=register2&inputFirstName=<?php echo escape(Session::get('inputFirstName')); ?>&inputLastName=<?php echo escape(Session::get('inputLastName')); ?>&inputEmail=<?php echo escape(Session::get('inputEmail')); ?>&inputGender=<?php echo escape(Session::get('inputGender')); ?>&token=<?php echo $token; ?>">modifier</a></p>
                        </div>
                    </div>
					<div id="lastNameGroup" class="form-group">
                    <label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Nom de famille</label>
                        <div class="col-lg-4">
							<p class="form-control-static"><?php echo escape(Session::get('inputLastName')); ?> <a class="small" href="index.php?action=register2&inputFirstName=<?php echo escape(Session::get('inputFirstName')); ?>&inputLastName=<?php echo escape(Session::get('inputLastName')); ?>&inputEmail=<?php echo escape(Session::get('inputEmail')); ?>&inputGender=<?php echo escape(Session::get('inputGender')); ?>&token=<?php echo $token; ?>">modifier</a></p>
                        </div>
                    </div>
					<div id="emailGroup" class="form-group">
                    <label for="inputEmail" class="col-lg-3 col-lg-offset-1 control-label">Courriel</label>
                        <div class="col-lg-4">                            
							<p class="form-control-static"><?php echo escape(Session::get('inputEmail')); ?> <a class="small" href="index.php?action=register2&inputFirstName=<?php echo escape(Session::get('inputFirstName')); ?>&inputLastName=<?php echo escape(Session::get('inputLastName')); ?>&inputEmail=<?php echo escape(Session::get('inputEmail')); ?>&inputGender=<?php echo escape(Session::get('inputGender')); ?>&token=<?php echo $token; ?>">modifier</a></p>
                        </div>
                    </div>
					<div id="userNameGroup" class="form-group">
                    <label for="inputUserName" class="col-lg-3 col-lg-offset-1 control-label">Nom d'utilisateur</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="inputUserName" name="inputUserName" placeholder="Nom d'utilisateur" value="<?php echo escape(Input::get('inputUserName')); ?>">
							<span class="help-block">Votre nom d'utilisateur vous est propre &mdash; il vous sert à vous connecter à votre compte.</span>
                        </div>
                    </div>
					<div id="passwordGroup" class="form-group">
                    <label for="inputPassword" class="col-lg-3 col-lg-offset-1 control-label">Mot de passe</label>
                        <div class="col-lg-4">
                            <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Mot de passe" value="">
                        </div>
                    </div>
					<div id="passwordAgainGroup" class="form-group">
                    <label for="inputPasswordAgain" class="col-lg-3 col-lg-offset-1 control-label">Mot de passe (encore)</label>
                        <div class="col-lg-4">
                            <input type="password" class="form-control" id="inputPasswordAgain" name="inputPasswordAgain" placeholder="Mot de passe (encore)" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-3">
                            <button type="submit" class="btn btn-primary">Continuer &raquo;</button> &nbsp;
							<a href="index.php?action=register2&inputFirstName=<?php echo escape(Session::get('inputFirstName')); ?>&inputLastName=<?php echo escape(Session::get('inputLastName')); ?>&inputEmail=<?php echo escape(Session::get('inputEmail')); ?>">Revenir</a>
                        </div>
                    </div>
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
            	</form>
            </div>
			<script>
			$(this).ready(function() {
				<?php echo $javascript; ?>
			});
			</script>