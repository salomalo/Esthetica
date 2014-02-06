<?php 
// STEP 1
// Ask for phone number

// Phone number for DB: preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", $input_lines);
// Phone number for display: preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $input_lines);	
	
$javascript = '';

$user = new User();

if($user->isLoggedIn()) {
	Redirect::to('accueil');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
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
		
		if($validation->passed()) {
			// Validation passed - up to second step now.
			Session::put('inputPhoneNumber', Input::get('inputPhoneNumber'));
			
			Redirect::to('register2&token=' . Token::generate());
		}
		else {
			$errors = '<div class="alert alert-danger"><strong>Oups!</strong> Veuillez corriger les problèmes suivants:<br />';
			foreach($validation->errors() as $key) {
				$errors .= $key['message'] . '<br />';
				$javascript .= '$(\'#' . $key['group'] . '\').addClass(\'has-error\').delay(500).effect("bounce"); ';
			}
			$errors .= '</div>';
		}
	}
	else {
		$errors = '<div class="alert alert-danger"><strong>Oups!</strong> Assurez-vous de soumettre le formulaire avec le bouton Continuer.</div>';
	}
}

?>
		<script type="text/javascript">
		$(document).ready(function(e) {
			$(document).attr('title', 'Inscription - Pose d\'ongles Trycia');
		});
		</script>
        <div class="row">
        	<div class="col-lg-offset-1 col-lg-10">            
            	<h1>Inscription <small>Première étape</small></h1>
				<ol class="breadcrumb">
					<li class="active">Étape 1</li>
					<li>Étape 2</li>
					<li>Étape 3</li>
					<li>Étape 4</li>
				</ol>
				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
						<span class="sr-only">25% complété</span>
					</div>
				</div>
				<?php echo (empty($errors)) ? '' : $errors; ?>
                <p>La création de votre compte débute ici. Veuillez entrer votre numéro de téléphone pour continuer. Il devriendra alors votre numéro de référence pour votre compte. Lors de vos achats, nous vous demanderons votre numéro de téléphone pour obtenir les informations de votre compte.</p>
				<h3>Informations</h3>
            	<form class="form-horizontal" role="form" action="index.php?action=register" method="post">
                    <div id="phoneNumberGroup" class="form-group">
                    <label for="inputPhoneNumber" class="col-lg-3 col-lg-offset-1 control-label">Numéro de téléphone</label>
                        <div class="col-lg-3">
                            <input type="tel" class="form-control" id="inputPhoneNumber" name="inputPhoneNumber" placeholder="(xxx) xxx-xxxx" value="<?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", Input::get('inputPhoneNumber'))); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-3">
                            <button type="submit" class="btn btn-primary">Continuer &raquo;</button> &nbsp;
							<a href="index.php?action=login">Annuler</a>
                        </div>
                    </div>
					<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
            	</form>
            </div>
			<script>
			$(this).ready(function() {
				<?php echo $javascript; ?>
			});
			</script>