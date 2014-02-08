<?php
	
$javascript = '';

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login');
}

if($user->data()->facebook_id != NULL) {
	
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
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
		
		if($validation->passed()) {
			$password = Input::get('inputPassword');
			$passwordAgain = Input::get('inputPasswordAgain');
			if(isset($password)) {
				if(isset($passwordAgain) && $password === $passwordAgain) {
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
		Redirect::to('update');
	}
}

$invoices = new Invoice();
$invoices->findAll($user->data()->id);

$invoicesCount = $invoices->data()->count();

$token = Token::generate();

$male = '';
$female = '';
// selected for the gender

if($user->data()->gender == 1) {
	$male = ' checked';
}
else if($user->data()->gender == 2) {
	$female = ' checked';
}

?>
		<script type="text/javascript">
		$(document).ready(function(e) {
			$(document).attr('title', 'Paramètres - Pose d\'ongles Trycia');
		});
		</script>
        <div class="col-md-12">            
			<h1>Mon compte <small>Gestion de clientèle</small></h1>
			<div class="list-group col-md-3">
				<p class="text-center">NAVIGATION</p>
				<a href="index.php?action=myaccount" class="list-group-item"><span class="glyphicon glyphicon-info-sign"></span> Mes détails</a>
				<a href="index.php?action=update" class="list-group-item active"><span class="glyphicon glyphicon-cog"></span> Paramètres</a>
				<p>&nbsp;</p>
				<a href="index.php?action=invoices" class="list-group-item"><span class="badge"><?php echo $invoicesCount; ?></span><span class="glyphicon glyphicon-shopping-cart"></span> Mes factures</a>
				<a href="index.php?action=credits" class="list-group-item"><span class="glyphicon glyphicon-usd"></span> Mes notes de crédit</a>
				<a href="index.php?action=myrdv" class="list-group-item"><span class="glyphicon glyphicon-calendar"></span> Mes rendez-vous</a>
				<p>&nbsp;</p>
				<a href="index.php?action=logout" class="list-group-item"><span class="glyphicon glyphicon-off"></span> Déconnexion</a>
			</div>
			
			<div class="tab-content col-md-9">
				<div class="tab-pane active" id="home">
					<h3>Mes paramètres</h3>
					<?php echo (empty($errors)) ? '' : $errors; ?>
					<form class="form-horizontal" role="form" action="index.php?action=update" method="post">
						<div id="phoneNumberGroup" class="form-group">
							<label for="inputPhoneNumber" class="col-lg-3 col-lg-offset-1 control-label">Numéro de téléphone:</label>
							<div class="col-lg-8">
								<p class="form-control-static"><?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $user->data()->phone)); ?>
								<span class="help-block">Vous devez <a href="index.php?action=contacteznous">nous contacter</a> pour changer ce numéro.</span></p>
							</div>
						</div>
						<div id="genderGroup" class="form-group">
							<label for="inputGender" class="col-lg-3 col-lg-offset-1 control-label">Sexe:</label>
							<div class="col-lg-8">
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
							<label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Prénom:</label>
							<div class="col-lg-3">
								<input type="text" class="form-control" id="inputFirstName" name="inputFirstName" placeholder="Votre prénom" value="<?php echo escape($user->data()->firstName); ?>">
							</div>
						</div>
						<div id="lastNameGroup" class="form-group">
							<label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Nom de famille:</label>
							<div class="col-lg-4">								
								<input type="text" class="form-control" id="inputLastName" name="inputLastName" placeholder="Votre nom de famille" value="<?php echo escape($user->data()->lastName); ?>">
							</div>
						</div>
						<div id="emailGroup" class="form-group">
							<label for="inputEmail" class="col-lg-3 col-lg-offset-1 control-label">Courriel:</label>
							<div class="col-lg-6">
                            <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Votre adresse courriel" value="<?php echo escape($user->data()->email); ?>">
							<span class="help-block">(facultatif)</span>
                        </div>
						</div>
						<div id="userNameGroup" class="form-group">
							<label for="inputUserName" class="col-lg-3 col-lg-offset-1 control-label">Nom d'utilisateur:</label>
							<div class="col-lg-8">
								<p class="form-control-static"><?php echo escape($user->data()->username); ?>
								<span class="help-block">Vous devez <a href="index.php?action=contacteznous">nous contacter</a> pour changer ceci.</span></p>
							</div>
						</div>
						<div id="passwordGroup" class="form-group">
						<label for="inputPassword" class="col-lg-3 col-lg-offset-1 control-label">Nouveau mot de passe:</label>
							<div class="col-lg-4">
								<input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Mot de passe" value="">
							</div>
						</div>
						<div id="passwordAgainGroup" class="form-group">
						<label for="inputPasswordAgain" class="col-lg-3 col-lg-offset-1 control-label">Nouveau mot de passe (encore):</label>
							<div class="col-lg-4">
								<input type="password" class="form-control" id="inputPasswordAgain" name="inputPasswordAgain" placeholder="Mot de passe (encore)" value="">
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-4 col-lg-8">
								<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Sauvegarder</button>
							</div>
						</div>
						<input type="hidden" name="token" value="<?php echo $token; ?>" />
					</form>
				</div>
			</div>
		</div>
		<script>
		$(document).ready(function() {
			<?php echo $javascript; ?>
		});
		</script>