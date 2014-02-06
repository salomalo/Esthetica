<?php 
// STEP 2
// Personnal information

// Phone number for DB: preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", $input_lines);
// Phone number for display: preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $input_lines);	
	
$javascript = '';
$male = '';
$female = '';
// selected for the gender

if(Input::get('inputGender') == 1) {
	$male = ' checked';
}
else if(Input::get('inputGender') == 2) {
	$female = ' checked';
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
			)
		));
		
		if($validation->passed()) {
			// redirect to step 3
			
			Session::put('inputGender', Input::get('inputGender'));
			Session::put('inputFirstName', Input::get('inputFirstName'));
			Session::put('inputLastName', Input::get('inputLastName'));
			Session::put('inputEmail', Input::get('inputEmail'));
			
			Redirect::to('register3&token=' . Token::generate());
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

if(!Token::check(Input::get('token'))) {
	Redirect::to('register');
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
            	<h1>Inscription <small>Deuxième étape</small></h1>
				<ol class="breadcrumb">
					<li><a href="index.php?action=register&inputPhoneNumber=<?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", Session::get('inputPhoneNumber'))); ?>&token=<?php echo $token; ?>">Étape 1</a></li>
					<li class="active">Étape 2</li>
					<li>Étape 3</li>
					<li>Étape 4</li>
				</ol>
				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
						<span class="sr-only">50% complété</span>
					</div>
				</div>
				<?php echo (empty($errors)) ? '' : $errors; ?>
                <p>Maintenant que votre numéro de téléphone a été validé, vous devez entrer vos informations personnelles pour votre compte en ligne. Ces informations seront disponibles aux préposés en salon. Lors de vos transactions, votre numéro de téléphone avec lequel vous avez créé ce compte vous sera demander afin d'associer les factures, les crédits et les rendez-vous à votre compte.</p>
				<h3>Informations</h3>
            	<form class="form-horizontal" role="form" action="index.php?action=register2" method="post">
                    <div id="phoneNumberGroup" class="form-group">
                    <label for="inputPhoneNumber" class="col-lg-3 col-lg-offset-1 control-label">Numéro de téléphone</label>
                        <div class="col-lg-3">
                            <p class="form-control-static"><?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", Session::get('inputPhoneNumber'))); ?> <a class="small" href="index.php?action=register&inputPhoneNumber=<?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", Session::get('inputPhoneNumber'))); ?>&token=<?php echo $token; ?>">modifier</a></p>
                        </div>
                    </div>
					<div id="genderGroup" class="form-group">
                    <label for="inputGender" class="col-lg-3 col-lg-offset-1 control-label">Sexe</label>
                        <div class="col-lg-3">
							<div class="radio">
							<label>
								<input type="radio" name="inputGender" id="maleRadio" value="1"<?php echo $male; ?>>
								Homme <img src="images/male.png" alt="Homme" />
							</label>
							</div>
							<div class="radio">
							<label>
								<input type="radio" name="inputGender" id="femaleRadio" value="2"<?php echo $female; ?>>
								Femme <img src="images/female.png" alt="Femme" />
							</label>
							</div>
                        </div>
                    </div>
					<div id="firstNameGroup" class="form-group">
                    <label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Prénom</label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="inputFirstName" name="inputFirstName" placeholder="Votre prénom" value="<?php echo escape(Input::get('inputFirstName')); ?>">
                        </div>
                    </div>
					<div id="lastNameGroup" class="form-group">
                    <label for="inputFirstName" class="col-lg-3 col-lg-offset-1 control-label">Nom de famille</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="inputLastName" name="inputLastName" placeholder="Votre nom de famille" value="<?php echo escape(Input::get('inputLastName')); ?>">
                        </div>
                    </div>
					<div id="emailGroup" class="form-group">
                    <label for="inputEmail" class="col-lg-3 col-lg-offset-1 control-label">Courriel</label>
                        <div class="col-lg-4">
                            <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Votre adresse courriel" value="<?php echo escape(Input::get('inputEmail')); ?>">
							<span class="help-block">(facultatif)</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-3">
                            <button type="submit" class="btn btn-primary">Continuer &raquo;</button> &nbsp;
							<a href="index.php?action=register&inputPhoneNumber=<?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", Session::get('inputPhoneNumber'))); ?>">Revenir</a>
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