<?php
	
$javascript = '';

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login');
}
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validation = new Validation();
		
		$validation->check($_POST, array(
			'inputCheck' => array(
				'required' => true,
				'fieldName' => 'Confirmation',
				'group' => 'checkGroup'
			)
		));
		
		if($validation->passed()) {
			$user->update(array('status' => 'Inactif'));
			$user->logout();
			Redirect::to('accueil');
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
		Redirect::to('deactivate');
	}
}

$invoices = new Invoice();
$invoices->findAll($user->data()->id);

$invoicesCount = 0;
if($invoices->exists()) {
	$invoicesCount = $invoices->data()->count();
}

$token = Token::generate();

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
					<h3>Désactivation du compte</h3>
					<?php echo (empty($errors)) ? '' : $errors; ?>
					<form class="form-horizontal" role="form" action="index.php?action=deactivate" method="post">
						<div id="userGroup" class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<span class="help-block text-danger"><strong>Vous allez désactiver votre compte.</strong> Votre compte n'apparaitera plus dans nos listes de clients. Vous pouvez le réactiver en TOUT temps en vous reconnectant à votre compte.</span>
							</div>
						</div>
						<div id="confirmationGroup" class="form-group">
							<label for="inputPhoneNumber" class="col-md-3 col-md-offset-1 control-label">Confirmation:</label>
							<div class="col-md-8">
								<div class="checkbox text-danger">
									<label>
										<input name="inputCheck" type="checkbox"> Je veux désactiver mon compte.
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-ban-circle"></span> Désactiver</button>
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