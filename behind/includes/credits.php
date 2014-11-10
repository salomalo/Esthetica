<?php

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login');
}

$credits = new Credit();
$credits->findAll($user->data()->id);

if($credits->exists()) {
	$results = $credits->data()->_results;
	foreach($results as $key => $credit) {
		$results[$key]->total = Credit::total($credit);
	}
}

$invoices = new Invoice();
$invoices->findAll($user->data()->id);
$invoicesCount = 0;
if($invoices->exists()) {
	$invoicesCount = $invoices->data()->count();
}

?>

		<script type="text/javascript">
		$(document).ready(function(e) {
			$(document).attr('title', 'Mes notes de crédits - Pose d\'ongles Trycia');
		});
		</script>
		<div class="col-md-12">            
			<h1>Mon compte <small>Gestion de clientèle</small></h1>
			<div class="list-group col-md-3">
				<a href="index.php?action=myaccount" class="list-group-item"><span class="glyphicon glyphicon-info-sign"></span> Mes détails</a>
				<a href="index.php?action=update" class="list-group-item"><span class="glyphicon glyphicon-cog"></span> Paramètres</a>
				<p>&nbsp;</p>
				<a href="index.php?action=invoices" class="list-group-item"><span class="badge"><?php echo $invoicesCount; ?></span><span class="glyphicon glyphicon-shopping-cart"></span> Mes factures</a>
				<a href="index.php?action=credits" class="list-group-item active"><span class="glyphicon glyphicon-usd"></span> Mes notes de crédit</a>
				<a href="index.php?action=myrdv" class="list-group-item"><span class="glyphicon glyphicon-calendar"></span> Mes rendez-vous</a>
				<p>&nbsp;</p>
				<a href="index.php?action=logout" class="list-group-item"><span class="glyphicon glyphicon-off"></span> Déconnexion</a>
			</div>
			
			<div class="tab-content col-md-9">
				<div class="tab-pane active" id="home">						
					<h3>Mes notes de crédit</h3>
					<table class="table table-striped table-hover table-responsive table-bordered">
						<thead>
							<tr>
								<th style="width: 10%;">#</th>
								<th>Date</th>
								<th style="width: 10%;" class="text-right">Montant</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(!$credits->exists()) {
								echo '
							<tr>
								<td colspan="3">Aucune note de crédit dans votre compte.</td>
							</tr>';
							}
							else {								
								foreach($results as $credit) {
									echo '
							<tr>
								<td><strong>' . $credit->id . '</strong></td>
								<td>' . substr($credit->date, 0, -9) . '</td>
								<td class="text-right">$' . substr(money_format("%i", (float)$invoice->total), 0, -4) . '</td>
							</tr>
';
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>