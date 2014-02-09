<?php

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login');
}

$invoices = new Invoice();
$invoices->findAll($user->data()->id);

$invoicesCount = 0;
if($invoices->exists()) {
	$invoicesCount = $invoices->data()->count();
	$results = $invoices->data()->_results;
	foreach($results as $key => $invoice) {
		
		$results[$key]->total = Invoice::total($invoice);
		
		if($invoice->status == "Payée") {
			$results[$key]->class = 'success';
			$results[$key]->icon = 'check';
		}
		else if($invoice->status == "Impayée") {
			$results[$key]->class = 'warning';
			$results[$key]->icon = 'usd';
		}
		else {
			$results[$key]->class = 'danger';
			$results[$key]->icon = 'times';
		}
	}
}

?>

			<script type="text/javascript">
			$(document).ready(function(e) {
				$(document).attr('title', 'Mes factures - Pose d\'ongles Trycia');
			});
			</script>
        	<div class="col-md-12">            
            	<h1>Mon compte <small>Gestion de clientèle</small></h1>
				<div class="list-group col-md-3">
					<p class="text-center">NAVIGATION</p>
					<a href="index.php?action=myaccount" class="list-group-item"><span class="glyphicon glyphicon-info-sign"></span> Mes détails</a>
					<a href="index.php?action=update" class="list-group-item"><span class="glyphicon glyphicon-cog"></span> Paramètres</a>
					<p>&nbsp;</p>
					<a href="index.php?action=invoices" class="list-group-item active"><span class="badge"><?php echo $invoicesCount; ?></span><span class="glyphicon glyphicon-shopping-cart"></span> Mes factures</a>
					<a href="index.php?action=credits" class="list-group-item"><span class="glyphicon glyphicon-usd"></span> Mes notes de crédit</a>
					<a href="index.php?action=myrdv" class="list-group-item"><span class="glyphicon glyphicon-calendar"></span> Mes rendez-vous</a>
					<p>&nbsp;</p>
					<a href="index.php?action=logout" class="list-group-item"><span class="glyphicon glyphicon-off"></span> Déconnexion</a>
				</div>
				
				<div class="tab-content col-md-9">
					<div class="tab-pane active" id="home">						
						<h3>Mes factures</h3>
						<table class="table table-striped table-hover table-responsive table-bordered">
							<thead>
								<tr>
									<th style="width: 10%;">#</th>
									<th>Date</th>
									<th style="width: 20%;">Statut</th>
									<th style="width: 10%;" class="text-right">Montant</th>
									<th style="width: 20%;">Options</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(!$invoices->exists()) {
									echo '
								<tr>
									<td colspan="5">Aucune facture dans votre compte.</td>
								</tr>';
								}
								else {								
									foreach($results as $invoice) {
										echo '
								<tr class="' . $invoice->class . '">
									<td><strong>' . $invoice->id . '</strong></td>
									<td>' . substr($invoice->date, 0, -9) . '</td>
									<td><i class="fa fa-' . $invoice->icon . ' fa-fw"></i> ' . $invoice->status . '</td>
									<td class="text-right">$' . substr(money_format("%i", (float)$invoice->total), 0, -4) . '</td>
									<td><a class="btn btn-primary btn-sm" href="index.php?action=invoice&id=' . $invoice->id . '"><span class="glyphicon glyphicon-eye-open"></span> Voir</a> ';
										if($invoice->status == "Impayée") {
											echo '<a href="index.php?action=invoice&id=' . $invoice->id . '&pay=1" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-usd"></span> Payer</a></td>';
										}
										else {
											echo '<a class="btn btn-default btn-sm disabled"><span class="glyphicon glyphicon-usd"></span> Payer</a>';
										}
										echo '
									</td>
								</tr>';
									}
								}
								?>
							</tbody>
						</table>
						<div class="row center-block" style="width: 50%">
							<table class="table table-hover table-bordered table-condensed table-responsive text-center" id="legend">
								<tr>
									<th class="text-center" colspan="3">LÉGENDE</th>
								</tr>
								<tr>
									<td class="success"><i class="fa fa-check"></i> Payée</td>
									<td class="warning"><i class="fa fa-usd"></i> Impayée</td>
									<td class="danger"><i class="fa fa-times"></i> Annulée</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>