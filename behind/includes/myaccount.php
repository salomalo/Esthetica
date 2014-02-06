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

$invoicesCount = $invoices->data()->count();

?>
			<script type="text/javascript">
			$(document).ready(function(e) {
				$(document).attr('title', 'Mon compte - Pose d\'ongles Trycia');
			});
			</script>
        	<div class="col-md-12">            
            	<h1>Mon compte <small>Gestion de clientèle</small></h1>
				<div class="list-group col-md-3">
					<p class="text-center">NAVIGATION</p>
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
                        <div class="col-md-6 col-xs-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                <h3 class="panel-title">MES DÉTAILS</h3>
                                </div>
                                <div class="panel-body">
									<img src="<?php echo $imageURL; ?>" class="img-thumbnail pull-right" style="height: 54px; width: 54px;" />
                                    <h3 class="name"><?php echo ($user->data()->gender == 1 ? '<i class="fa fa-lg fa-male fa-fw"></i>' : '<i class="fa fa-lg fa-female fa-fw"></i>'); ?> <?php echo escape($user->data()->firstName) . ' ' . escape($user->data()->lastName); ?></h3>
                                	<table border="0" class="stats" style="margin-top: 20px;">
                                        <?php
										echo ($user->data()->facebook_id != NULL) ? '<tr>
                                            <th align="right" valign="top" scope="row"><span class="glyphicon glyphicon-link"></span> Connexion:</th>
                                            <td align="left" valign="top"><i class="fa fa-facebook fa-fw"></i> Facebook</td>
                                        </tr>' : '';
										?>
                                        <tr>
                                            <th align="right" valign="top" scope="row"><span class="glyphicon glyphicon-earphone"></span> Téléphone:</th>
                                            <td align="left" valign="top"><?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $user->data()->phone)); ?></td>
                                        </tr>
                                        <tr>
                                            <th align="right" valign="top" scope="row"><span class="glyphicon glyphicon-inbox"></span> Courriel:</th>
                                            <td align="left" valign="top"><a class="hidden-xs" href="mailto:<?php echo escape($user->data()->email); ?>"><?php echo escape($user->data()->email); ?></a></td>
                                        </tr>
                                        <tr class="visible-xs">
                                            <td colspan="2" align="center" valign="top"><a href="mailto:<?php echo escape($user->data()->email); ?>"><?php echo escape($user->data()->email); ?></a></td>
                                        </tr>
                                        <tr>
                                            <th align="right" valign="top" scope="row"><span class="glyphicon glyphicon-user"></span> Utilisateur:</th>
                                            <td align="left" valign="top"><?php
											if($user->data()->facebook_id) {
												echo '<em>Aucun</em>';
											}
											else {
												echo escape($user->data()->username);
											} ?></td>
                                        </tr>
                                        <tr>
                                            <th align="right" valign="top" scope="row"><span class="glyphicon glyphicon-asterisk"></span> Mot de passe:</th>
                                            <td align="left" valign="top"><?php
											if($user->data()->facebook_id) {
												echo '<em>Aucun</em>';
											}
											else {
												echo '********* <a href="index.php?action=update">(modifier)</a>';
											} ?></td>
                                        </tr>
                                    </table>
                                    <a class="btn btn-primary btn-block" style="margin-top: 10px;" href="index.php?action=update"><span class="glyphicon glyphicon-edit"></span> Modifier</a>
                                    <a class="btn btn-danger btn-block" style="margin-top: 5px;" href="index.php?action=update"><span class="glyphicon glyphicon-ban-circle"></span> Désactiver mon compte</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                <h3 class="panel-title">MES STATISTIQUES</h3>
                                </div>
                                <div class="panel-body">
                                    <table border="0" class="stats">
                                        <tr>
                                            <th align="right" valign="top" scope="row"><span class="glyphicon glyphicon-tags"></span> Factures:</th>
                                            <td align="left" valign="top"><a href="index.php?action=invoices"><?php echo $invoicesCount; ?> factures</a></td>
                                        </tr>
                                        <tr>
                                            <th align="right" valign="top" scope="row"><span class="glyphicon glyphicon-usd"></span> Balance crédits:</th>
                                            <td align="left" valign="top"><span class="text-success">$0.50 CAD</span></td>
                                        </tr>
                                        <tr>
                                            <th align="right" valign="top" scope="row"><span class="glyphicon glyphicon-eye-open"></span> Esthéticien(ne):</th>
                                            <td align="left" valign="top"><a href="mailto:test@test.com"><i class="fa fa-female"></i> Trycia</a></td>
                                        </tr>
                                        <tr>
                                            <th align="right" valign="top" scope="row"><span class="glyphicon glyphicon-calendar"></span> Prochain rendez-vous:</th>
                                            <td align="left" valign="top"><a href="index.php?action=myrdv">8 mars 2014</a></td>
                                        </tr>
                                    </table>
                                    <h4>Votre code client:</h4>
                                    <p class="text-center small" style="font-weight:bold;margin-bottom:0px;"><?php printf("CPOT-%1$06d", $user->data()->id); ?></p>
                                  	<p><img src="http://www.barcodesinc.com/generator/image.php?code=<?php printf("CPOT-%1$06d", $user->data()->id); ?>%20&style=68&type=C128B&width=200&height=75&xres=1&font=3" class="img-responsive center-block" /></p>
                                </div>
                            </div>	
						</div>
					</div>
				</div>
			</div>