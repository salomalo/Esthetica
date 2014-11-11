<?php

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login');
}

if($user->data()->facebook_id == NULL) {
	// male
	if($user->data()->gender == 1) {
		$imageURL = "https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/yL/r/HsTZSDw4avx.gif";
	}
	// female
	else {
		$imageURL = "https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/yp/r/yDnr5YfbJCH.gif";
	}
}
else {
	$imageURL = "https://graph.facebook.com/" . $user->data()->facebook_id . "/picture";
}

$invoices = new Invoice();
$invoices->findAll($user->data()->id);

$invoicesCount = 0;
if($invoices->exists()) {
	$invoicesCount = $invoices->data()->count();
}
$credits = new Credit();
$credits->findAll($user->data()->id);

$creditsTotal = 0.00;
$creditsTextClass = "";

if($credits->exists()) {
	foreach($credits->data() as $credit) {
		$creditsTotal += Credit::total($credit);
	}
	if($recitsTotal > 0) {
		$creditsTextClass = "text-success";	
	}
}

?>
			<script type="text/javascript">
			$(document).ready(function(e) {
				$(document).attr('title', 'Mon compte - Pose d\'ongles Trycia');
			});
			</script>
        	<div class="col-md-12">            
            	<h1>Mon compte <small>Gestion de clientèle</small></h1>
				<div class="list-group col-md-3">
					<a href="index.php?action=myaccount" class="list-group-item active"><span class="glyphicon glyphicon-info-sign"></span> Mes détails</a>
					<a href="index.php?action=update" class="list-group-item"><span class="glyphicon glyphicon-cog"></span> Paramètres</a>
					<p>&nbsp;</p>
					<a href="index.php?action=invoices" class="list-group-item"><span class="badge"><?php echo $invoicesCount; ?></span><span class="glyphicon glyphicon-shopping-cart"></span> Mes factures</a>
					<a href="index.php?action=credits" class="list-group-item"><span class="glyphicon glyphicon-usd"></span> Mes notes de crédit</a>
					<a href="index.php?action=myrdv" class="list-group-item"><span class="glyphicon glyphicon-calendar"></span> Mes rendez-vous</a>
					<p>&nbsp;</p>
					<a href="index.php?action=logout" class="list-group-item"><span class="glyphicon glyphicon-off"></span> Déconnexion</a>
				</div>
				
				<div class="tab-content col-md-9">
					<div class="tab-pane active" id="home">						
						<h3>Mes détails personnels</h3>
						<div class="row">
							<div class="col-md-6 col-xs-12">
								<div class="panel panel-primary panel-stats">
									<div class="panel-heading">
									<h3 class="panel-title">MES DÉTAILS</h3>
									</div>
									<div class="panel-body">
										<?php if($user->data()->facebook_id) { ?>
										<img src="<?php echo $imageURL; ?>" class="img-thumbnail pull-right" style="height: 60px; width: 60px; position: absolute; top: 50px; right: 25px;" />
										<?php } ?>
										<h4 class="name"><?php echo ($user->data()->gender == 1 ? '<i class="fa fa-lg fa-male"></i>' : '<i class="fa fa-lg fa-female"></i>'); ?> <?php echo escape($user->data()->firstName) . ' ' . escape($user->data()->lastName); ?></h4>
										<ul class="list-unstyled stats">
											<li><span class="glyphicon glyphicon-link"></span> Type de connexion:</li>
											<li class="data"><?php
											echo ($user->data()->facebook_id != NULL) ? '
												<td align="left" valign="top"><i class="fa fa-fw fa-lg fa-facebook"></i> Facebook</td>
' : '<td align="left" valign="top"><i class="fa fa-fw fa-lg fa-asterisk"></i> Mot de passe</td>';
											?></li>
											<li><span class="glyphicon glyphicon-earphone"></span> Numéro de téléphone:</li>
											<li class="data"><?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $user->data()->phone)); ?></li>
											<li><span class="glyphicon glyphicon-inbox"></span> Courriel:</li>
											<li class="data"><a href="mailto:<?php echo escape($user->data()->email); ?>"><?php echo escape($user->data()->email); ?></a></li>
											<li><span class="glyphicon glyphicon-user"></span> Nom d'utilisateur:</li>
											<li class="data"><?php
												if($user->data()->facebook_id) {
													echo '<em>Aucun</em>';
												}
												else {
													echo escape($user->data()->username);
												} ?></li>
											<li><span class="glyphicon glyphicon-asterisk"></span> Mot de passe:</li>
											<li class="data"><?php
												if($user->data()->facebook_id) {
													echo '<em>Aucun</em>';
												}
												else {
													echo '********* <a href="index.php?action=update">(modifier)</a>';
												} ?></li>
										</ul>
										
										
									</div>
								</div>
							</div>
							<div class="col-md-6 col-xs-12">
								<div class="panel panel-primary panel-stats">
									<div class="panel-heading">
									<h3 class="panel-title">MES STATISTIQUES</h3>
									</div>
									<div class="panel-body">
										<ul class="list-unstyled stats clearfix">
											<li><span class="glyphicon glyphicon-tags"></span> Mes factures:</li>
											<li class="data"><a href="index.php?action=invoices"><?php echo $invoicesCount; ?> factures</a></li>
											<li><span class="glyphicon glyphicon-usd"></span> Balance de compte:</li>
											<li class="data"><span class="<?php echo $creditsTextClass; ?>">$<?php echo substr(money_format("%i", $creditsTotal), 0, -4); ?> disponible</span></li>
											<li><span class="glyphicon glyphicon-eye-open"></span> Votre esthéticien(ne):</li>
											<li class="data"><a href="mailto:test@test.com"><i class="fa fa-female"></i> Trycia</a></li>
											<li><span class="glyphicon glyphicon-calendar"></span> Votre prochain rendez-vous:</li>
											<li class="data"><a href="index.php?action=myrdv">8 mars 2014</a></li>
											<li><span class="glyphicon glyphicon-barcode"></span> Votre code client:</li>
											<li class="data"><p class="small">Faites scanner ce code pour faciliter l'accès à votre compte.</p></li>
											<li style="margin-top: -20px;"><img src="http://www.barcodesinc.com/generator/image.php?code=<?php echo $user->data()->phone; ?>%20&style=68&type=C128B&width=200&height=50&xres=1&font=3" class="img-responsive barcode" /></li>
										</ul>
										<br class="clearfix" />
									</div>
								</div>	
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-primary">
									<div class="panel-heading">
									<h3 class="panel-title">ACTIONS DE COMPTE</h3>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-md-4 col-md-offset-2">
												<a class="btn btn-primary btn-block" href="index.php?action=update"><span class="glyphicon glyphicon-edit"></span> Modifier</a>
											</div>
											<div class="col-md-4">
												<a class="btn btn-danger btn-block" href="index.php?action=deactivate"><span class="glyphicon glyphicon-ban-circle"></span> Désactiver mon compte</a>
											</div>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>