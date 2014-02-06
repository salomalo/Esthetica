<?php 
// STEP 4
// Execute.

// Phone number for DB: preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", $input_lines);
// Phone number for display: preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $input_lines);	

if(Token::check(Input::get('token'))) {	
	$user = new User();
	$salt = Hash::salt(32);
	try {
		$user->create(array(
			'username'	=> Session::get('inputUserName'),
			'password'	=> Hash::make(Session::get('inputPassword'), $salt),
			'salt'		=> $salt,
			'phone'		=> preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", Session::get('inputPhoneNumber')),
			'gender'	=> Session::get('inputGender'),
			'firstName'	=> Session::get('inputFirstName'),
			'lastName'	=> Session::get('inputLastName'),
			'email'		=> Session::get('inputEmail'),
			'joined'	=> date('Y-m-d H:i:s'),
			'clientGroup'=> 1
		));	
	}
	catch(Exception $e) {
		die($e->getMessage());
	}
}
else {
	//Redirect::to('register');
}

?>
		<script type="text/javascript">
		$(document).ready(function(e) {
			$(document).attr('title', 'Inscription - Pose d\'ongles Trycia');
		});
		</script>
        <div class="row">
        	<div class="col-lg-offset-1 col-lg-10">            
            	<h1>Inscription <small>Étape finale</small></h1>
				<ol class="breadcrumb">
					<li>Étape 1</li>
					<li>Étape 2</li>
					<li>Étape 3</li>
					<li class="active">Étape 4</li>
				</ol>
				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
						<span class="sr-only">100% complété</span>
					</div>
				</div>
				<?php echo (empty($errors)) ? '' : $errors; ?>
                <p>Votre compte client a été créé avec succès! Vous pouvez donc maintenant vous connecter avec vos informations personnelles. Il vous est possible de changer ces informations, toutefois voici comment votre compte a été configuré:</p>
				<h3>Informations</h3>
            	<form class="form-horizontal" role="form" method="post">
                    <div id="phoneNumberGroup" class="form-group">
                    <label for="inputPhoneNumber" class="col-lg-3 col-lg-offset-1 control-label">Numéro de téléphone</label>
                        <div class="col-lg-3">
                            <p class="form-control-static"><?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", Session::get('inputPhoneNumber'))); ?></p>
                        </div>
                    </div>	
					<div id="genderGroup" class="form-group">
                    <label for="inputGender" class="col-lg-3 col-lg-offset-1 control-label">Sexe</label>
                        <div class="col-lg-3">
							<p class="form-control-static"><?php echo (Session::get('inputGender') == 1 ? 'Homme <img src="images/male.png" alt="Homme" />' : 'Femme <img src="images/female.png" alt="Femme" />'); ?></p>
                        </div>
                    </div>
					<div id="firstNameGroup" class="form-group">
                    <label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Prénom</label>
                        <div class="col-lg-3">
							<p class="form-control-static"><?php echo escape(Session::get('inputFirstName')); ?></p>
                        </div>
                    </div>
					<div id="lastNameGroup" class="form-group">
                    <label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Nom de famille</label>
                        <div class="col-lg-4">
							<p class="form-control-static"><?php echo escape(Session::get('inputLastName')); ?></p>
                        </div>
                    </div>
					<div id="emailGroup" class="form-group">
                    <label for="inputEmail" class="col-lg-3 col-lg-offset-1 control-label">Courriel</label>
                        <div class="col-lg-4">                            
							<p class="form-control-static"><?php echo escape(Session::get('inputEmail')); ?></p>
                        </div>
                    </div>
					<div id="userNameGroup" class="form-group">
                    <label for="inputUserName" class="col-lg-3 col-lg-offset-1 control-label">Nom d'utilisateur</label>
                        <div class="col-lg-4">
							<p class="form-control-static"><?php echo escape(Session::get('inputUserName')); ?></p>
                        </div>
                    </div>
					<div id="passwordGroup" class="form-group">
                    <label for="inputPassword" class="col-lg-3 col-lg-offset-1 control-label">Mot de passe</label>
                        <div class="col-lg-4">
							<p class="form-control-static"><?php echo preg_replace("/./", "*", escape(Session::get('inputPassword'))); ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-3">
                            <a href="index.php?action=login" class="btn btn-success btn-lg" role="button">Continuer à la page de connexion &raquo;</a>
                        </div>
                    </div>
            	</form>
            </div>