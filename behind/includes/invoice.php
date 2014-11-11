<?php

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('login');
}

$invoice = new Invoice(intval(Input::get('id')));

if(!$invoice->exists()) {
	Redirect::to('invoices');
}
if($invoice->data()->user_id != $user->data()->id) {
	Redirect::to('invoices');
}

$data['user_id']	= $user->data()->id;
$data['status']		= 'Payée';
$data['date']		= "2013-12-12 00:00:00";
$lines[] 			= array(	'sku' 			=>	'7584230975',
								'description'	=>	'Prothèses LÉOPARD (paquet de 5)',
								'qty'			=>	2,
								'price'			=>	12.99);
$lines[] 			= array(	'sku' 			=>	'POSEPROT',
								'description'	=>	'Pose de prothèses',
								'qty'			=>	1,
								'price'			=>	25.00);
$total = 0.00;
foreach($lines as $line) {
	$total += $line['price']*$line['qty'];
}
$data['data'] 		= serialize($lines);
$data['credit'] 	= 0.00;
$total -= $data['credit'];
$data['taxes'] 		= serialize(array(	0 => array(	'name'	=>	'TPS (5.000%)',
											'total' =>	$total*5/100),
								1 => array(	'name'	=>	'TVQ (9.975%)',
											'total'	=>	$total*9.975/100)));
										
/*echo '<pre>';
print_r($data);
echo '</pre';*/

$invoices = new Invoice();
$invoices->findAll($user->data()->id);

$invoicesCount = 0;
if($invoices->exists()) {
	$invoicesCount = $invoices->data()->count();
}
?>

		<script type="text/javascript">
		$(document).ready(function(e) {
			$(document).attr('title', 'Facture #<?php echo $invoice->data()->id; ?> - Pose d\'ongles Trycia');
		});
		</script>
		<div class="col-md-12">            
			<h1 id="pageTitle">Mon compte <small>Gestion de clientèle</small></h1>
			<div id="navigation" class="list-group col-md-3">
				<a href="index.php?action=myaccount" class="list-group-item"><span class="glyphicon glyphicon-info-sign"></span> Mes détails</a>
				<a href="index.php?action=update" class="list-group-item"><span class="glyphicon glyphicon-cog"></span> Paramètres</a>
				<p>&nbsp;</p>
				<a href="index.php?action=invoices" class="list-group-item active"><span class="badge"><?php echo $invoicesCount; ?></span><span class="glyphicon glyphicon-shopping-cart"></span> Mes factures</a>
				<a href="index.php?action=credits" class="list-group-item"><span class="glyphicon glyphicon-usd"></span> Mes notes de crédit</a>
				<a href="index.php?action=myrdv" class="list-group-item"><span class="glyphicon glyphicon-calendar"></span> Mes rendez-vous</a>
				<p>&nbsp;</p>
				<a href="index.php?action=logout" class="list-group-item"><span class="glyphicon glyphicon-off"></span> Déconnexion</a>
			</div>
			
			<div id="facture" class="tab-content col-md-9">
				<div class="row">
					<div class="col-xs-6">
						<a href="index.php?action=invoices" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Retourner à la liste</a>
						<?php
						if($invoice->data()->status === "Impayée") {
							echo '<a href="index.php?action=invoice&id=' . $invoice->data()->id . '&pay=1" class="btn btn-success"><span class="glyphicon glyphicon-usd"></span> Payer</a>';
						}
						?>
					</div>
					<div class="col-xs-6 text-right">
						<h2 style="margin-top: 0px; margin-bottom: 20px; font-weight: bold;">FACTURE</h2>
					</div>
				</div>
				
				<div class="row">
					<div class="col-xs-12">
						<?php
						if($invoice->data()->status == "Payée") {
							echo '
								<div class="panel panel-success">
									<div class="panel-heading">
										<h4>Statut du paiement</h4>
									</div>
									<div class="panel-body">
										<p class="text-success text-center"><i class="fa fa-check"></i> PAIEMENT REÇU &mdash; MERCI</p>
									</div>
								</div>';
						}
						else if($invoice->data()->status == "Impayée") {
							echo '
								<div class="panel panel-warning">
									<div class="panel-heading">
										<h4>Statut du paiement</h4>
									</div>
									<div class="panel-body">
										<p class="text-warning text-center"><i class="fa fa-usd"></i> EN ATTENTE DU PAIEMENT &mdash; Payez ci-haut ou en magasin.</p>
									</div>
								</div>';
						}
						else if($invoice->data()->status == "Annulée") {
							echo '
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4>Statut du paiement</h4>
									</div>
									<div class="panel-body">
										<p class="text-danger text-center"><i class="fa fa-times"></i> ANNULÉE &mdash; Si c\'est une erreur, veuillez nous contacter.</p>
									</div>
								</div>';
						}
						?>
					</div>
				</div>
				
				<div class="row">
					<div class="col-xs-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4>Vendeur</h4>
							</div>
							<div class="panel-body">
								<h4 class="name">Pose d'ongles Trycia inc.</h4>
								<p style="margin: 0;"><span class="glyphicon glyphicon-barcode"></span> Facture #<?php echo $invoice->data()->id; ?></p>		
								<p style="margin: 0;"><span class="glyphicon glyphicon-calendar"></span> <?php echo substr($invoice->data()->date, 0, -9); ?></p>
							</div>
						</div>
					</div>
					<div class="col-xs-6 text-right">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4>Client</h4>
							</div>
							<div class="panel-body">
								<h4 class="name"><?php echo escape($user->data()->firstName) . ' ' . escape($user->data()->lastName); ?></h4>
								<p style="margin: 0;"><span class="glyphicon glyphicon-earphone"></span> <?php echo escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $user->data()->phone)); ?></p>
								<p style="margin: 0;"><span class="glyphicon glyphicon-inbox"></span> <?php echo ($user->data()->email !== "") ? escape($user->data()->email) : '<em>Aucun courriel</em>'; ?></p>
							</div>
						</div>
					</div>
				</div> <!-- / end client details section -->
	
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><h4>SKU</h4></th>
							<th><h4>Description</h4></th>
							<th><h4>Quantité</h4></th>
							<th><h4>Prix</h4></th>
							<th><h4>Sous-Total</h4></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$data = unserialize($invoice->data()->data);
						
						$subtotal = 0;
						foreach($data as $line) {
							echo '								
						<tr>
							<td>' . $line['sku'] . '</td>
							<td>' . $line['description'] . '</td>
							<td class="text-right">' . $line['qty'] . '</td>
							<td class="text-right">$' . substr(money_format("%i", floatval($line['price'])), 0, -4) . '</td>
							<td class="text-right">$' . substr(money_format("%i", floatval($line['price'])*intval($line['qty'])), 0, -4) . '</td>
						</tr>
							';
							$subtotal += floatval($line['price'])*intval($line['qty']);
						}
						
						if($invoice->data()->credit > 0) {
							echo '
						<tr>
							<td class="text-right text-success" colspan="4">Crédit compte client:</td>
							<td class="text-right text-success">$' . substr(money_format("%i", -(float)$invoice->data()->credit), 0, -5) . ')</td>
						</tr>
';
							$subtotal -= (float)$invoice->data()->credit;
						}
						?>
					</tbody>
				</table>
				<script type="text/javascript">
				$(document).ready(function(e) {	
					$('#print').click(function(e) {
						window.print();
					});
				});
				</script>
				<div class="row">
					<div class="col-xs-3">
						<button id="print" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Imprimer</button>
					</div>
					<div class="col-xs-3 col-xs-offset-4 text-right">
						<h5 style="margin-top: 0px;">
						Sous-Total :<br />
						<?php
						$taxes = unserialize($invoice->data()->taxes);
						foreach($taxes as $tax) {
							echo $tax['name'] . ' :<br />';
						}
						?></h5>
						<h4 style="font-weight: bold;">TOTAL :</h4>
					</div>
					<div class="col-xs-2 text-right">
						<h5 style="margin-top: 0px;">
						<?php
						echo '$' . substr(money_format("%i", $subtotal), 0, -4) . '<br />';
						$taxesAmmount = 0;
						foreach($taxes as $tax) {
							echo '$' . substr(money_format("%i", (float)$tax['total']), 0, -4) . '<br />';
							$taxesAmmount += (float)$tax['total'];
						}
						$total = $subtotal + $taxesAmmount;
						?></h5>
						<h4 style="font-weight: bold;">$<?php echo substr(money_format("%i", (float)$total), 0, -4); ?></h4>
					</div>
				</div>
			</div>
		</div>