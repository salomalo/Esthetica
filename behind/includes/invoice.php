<?php
if	(!$user->isLoggedIn())	{
				Redirect::to('login');
}

$userRebate	=	json_decode($user->data()->clientGroupData)->rebate;
?>

<div class="col-md-12">
    <h1 id="pageTitle">Mon compte <small>Gestion de clientèle</small></h1>
    <div class="list-group col-md-3">
								<?php
								include	'back-sidebar.php';

								$invoice	=	new	Invoice();

								foreach	($invoicesResults	as	$key	=>	$tempInvoice)	{
												if	($tempInvoice->id	==	intval(Input::get('id')))	{
																$invoice->setData($tempInvoice);
																break;
												}
								}

								/**
									* Invoice payment handler
									*/
								if	(Input::get('pay'))	{
												if	($invoice->data()->status	==	"Impayée")	{
																$payments	=	json_decode($invoice->data()->payments);
																$payments[]	=	array('type'	=>	'Paiement internet',	'amount'	=>	$invoice->data()->totalDue);
																Invoice::markPaidWithId($invoice->data()->id,	$payments);
												}	else	{
																Session::flash('flash',	array('status'	=>	'error',	'message'	=>	'<strong>Erreur!</strong> Cette facture (#'	.	$invoice->data()->id	.	') est déjà marquée comme payée.'));
												}
												Redirect::to('invoice&id='	.	$invoice->data()->id);
								}

								/**
									* DEBUG UNPAY
									*/
								if	(Input::get('unpay'))	{
												if	($invoice->data()->status	==	"Payée")	{
																Invoice::markUnpaidWithId($invoice->data()->id,	true);
												}	else	{
																Session::flash('flash',	array('status'	=>	'error',	'message'	=>	'<strong>Erreur!</strong> Cette facture (#'	.	$invoice->data()->id	.	') est déjà marquée comme impayée.'));
												}
												Redirect::to('invoice&id='	.	$invoice->data()->id);
								}


								if	(!$invoice->exists())	{
												Session::flash('flash',	array('status'	=>	'error',	'message'	=>	'<strong>Erreur!</strong> Cette facture (#'	.	Input::get('id')	.	') n\'existe pas. Veuillez nous aviser si vous avez cliqué un lien valide.'));
												Redirect::to('invoices');
								}
								if	($invoice->data()->user_id	!=	$user->data()->id)	{
												Session::flash('flash',	array('status'	=>	'error',	'message'	=>	'<strong>Erreur!</strong> Cette facture (#'	.	Input::get('id')	.	') n\'est pas associée à votre compte. Veuillez nous aviser si vous avez cliqué un lien valide.'));
												Redirect::to('invoices');
								}
								?>
    </div>
    <script type="text/javascript">
								$(document).ready(function (e) {
												$(document).attr('title', 'Facture #<?php	echo	$invoice->data()->id;	?> - Esthética');
								});
    </script>

    <div id="facture" class="tab-content col-md-9">
        <div class="row">
            <div class="col-xs-6">
                <a href="index.php?action=invoices" class="btn btn-default"><i class="fa fa-fw fa-arrow-left"></i> Retourner à la liste</a>
																<?php
																if	($invoice->data()->status	===	"Impayée")	{
																				echo	'<a href="index.php?action=invoice&id='	.	$invoice->data()->id	.	'&pay=1" class="btn btn-success"><i class="fa fa-fw fa-dollar"></i> Payer</a>';
																}	else	{
																				echo	'<a href="index.php?action=invoice&id='	.	$invoice->data()->id	.	'&unpay=1" class="btn btn-danger"><i class="fa fa-fw fa-dollar"></i> IMPAYER (DEBUG)</a>';
																}
																?>
																<h3 class="visible-print" style="margin-top: 0; margin-bottom: 0;">
																				<img style="max-height: 60px;" src="images/logo-site.png" />
																</h3>
            </div>
            <div class="col-xs-6 text-right">
                <h3 style="margin-top: 0px; margin-bottom: 20px; font-weight: bold;">FACTURE</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
																<?php
																if	($invoice->data()->status	==	"Payée")	{
																				echo	'
								<div class="panel panel-success">
									<div class="panel-heading">
										<span class="text-white">Statut du paiement</span>
									</div>
									<div class="panel-body">
										<p class="text-success text-center no-bottom-margin"><i class="fa fa-fw fa-check"></i> PAIEMENT REÇU &mdash; MERCI</p>
									</div>
								</div>';
																}	else	if	($invoice->data()->status	==	"Impayée")	{
																				echo	'
								<div class="panel panel-warning">
									<div class="panel-heading">
										<span class="text-white">Statut du paiement</span>
									</div>
									<div class="panel-body">
										<p class="text-warning text-center no-bottom-margin"><i class="fa fa-fw fa-dollar"></i> EN ATTENTE DU PAIEMENT &mdash; Payez ci-haut ou en magasin.</p>
									</div>
								</div>';
																}	else	if	($invoice->data()->status	==	"Annulée")	{
																				echo	'
								<div class="panel panel-danger">
									<div class="panel-heading">
										<span class="text-white">Statut du paiement</span>
									</div>
									<div class="panel-body">
										<p class="text-danger text-center no-bottom-margin"><i class="fa fa-fw fa-times"></i> ANNULÉE &mdash; Si c\'est une erreur, veuillez nous contacter.</p>
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
                        Vendeur
                    </div>
                    <div class="panel-body">
                        <h5 class="name">Esthética inc.</h5>
                        <p style="margin: 0;"><i class="fa fa-fw fa-barcode"></i> Facture #<?php	echo	$invoice->data()->id;	?></p>
                        <p style="margin: 0;"><i class="fa fa-fw fa-calendar"></i> <?php	echo	substr($invoice->data()->date,	0,	-9);	?></p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 text-right">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Client
                    </div>
                    <div class="panel-body">
                        <h5 class="name"><?php	echo	escape($user->data()->firstName)	.	' '	.	escape($user->data()->lastName);	?></h5>
                        <p style="margin: 0;"><i class="fa fa-fw fa-phone"></i> <?php	echo	escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/",	"($1) $2-$3",	$user->data()->phone));	?></p>
                        <p style="margin: 0;"><i class="fa fa-fw fa-inbox"></i> <?php	echo	($user->data()->email	!==	"")	?	escape($user->data()->email)	:	'<em>Aucun courriel</em>';	?></p>
                    </div>
                </div>
            </div>
        </div> <!-- / end client details section -->

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Sous-Total</th>
                </tr>
            </thead>
            <tbody>
																<?php
																$data	=	json_decode($invoice->data()->data);
																$payments	=	json_decode($invoice->data()->payments);

																$subtotal	=	0;
																foreach	($data	as	$line)	{
																				echo	'
						<tr>
							<td>'	.	$line->sku	.	'</td>
							<td>'	.	$line->description	.	'</td>
							<td class="text-right">'	.	$line->qty	.	'</td>
							<td class="text-right">$'	.	substr(money_format("%i",	floatval($line->price)),	0,	-4)	.	'</td>
							<td class="text-right">$'	.	substr(money_format("%i",	floatval($line->price)	*	intval($line->qty)),	0,	-4)	.	'</td>
						</tr>
							';
																				$subtotal	+=	floatval($line->price)	*	intval($line->qty);
																}

																if	($userRebate	>	0)	{
																				$subtotalBefore	=	$subtotal;
																				$temp	=	1	-	(intval(json_decode($user->data()->clientGroupData)->rebate)	/	100);
																				$subtotal	=	$subtotal	*	$temp;

																				$totalRebate	=	$subtotalBefore	-	$subtotal;
																				echo	'
						<tr>
							<td class="text-right text-success" colspan="4">Rabais compte client ('	.	$userRebate	.	'%):</td>
							<td class="text-right text-success">$'	.	substr(money_format("%i",	-(float)	$totalRebate),	0,	-5)	.	')</td>
						</tr>
';
																}
																/* echo	json_encode(array(
																	 array(
																	 'type'	=>	'credit',
																	 'amount'	=>	10,
																	 ),
																	 array(
																	 'type'	=>	'visa',
																	 'amount'	=>	5.50,
																	 )
																	 )); */

																if	($payments)	{
																				foreach	($payments	as	$payment)	{
																								echo	'
						<tr>
							<td class="text-right text-success" colspan="4">'	.	$payment->type	.	'</td>
							<td class="text-right text-success">$'	.	substr(money_format("%i",	-floatval($payment->amount)),	0,	-5)	.	')</td>
						</tr>';
																				}
																}

																/**
																	* Let's be safe. :D
																	*/
																if	($subtotal	<	0)	{
																				$subtotal	=	0;
																}
																?>
            </tbody>
        </table>
        <script type="text/javascript">
												$(document).ready(function (e) {
																$('#print').click(function (e) {
																				window.print();
																});
												});
        </script>
        <div class="row">
            <div class="col-xs-3">
                <button id="print" class="btn btn-primary"><i class="fa fa-print"></i> Imprimer</button>
            </div>
            <div class="col-xs-5 col-xs-offset-2 text-right">
                Sous-Total :<br />
																<?php
																$taxes	=	json_decode($invoice->data()->taxes);
																foreach	($taxes	as	$tax)	{
																				echo	$tax->name	.	' ('	.	number_format($tax->percentage,	3)	.	'%):<br />';
																}
																?>
																<h5>TOTAL :</h5>
                <h5>TOTAL À PAYER :</h5>
            </div>
            <div class="col-xs-2 text-right">
																<?php
																echo	'$'	.	substr(money_format("%i",	$subtotal),	0,	-4)	.	'<br />';
																$taxesAmount	=	0.0;
																foreach	($taxes	as	$tax)	{
																				$taxCurrent	=	round($subtotal	*	(	(float)	$tax->percentage	/	100	),	2);

																				echo	'$'	.	substr(money_format("%i",	(float)	$taxCurrent),	0,	-4)	.	'<br />';
																				$taxesAmount	+=	(float)	$taxCurrent;
																}
																$total	=	$subtotal	+	$taxesAmount;
																$totalDue	=	$total;

																if	($payments)	{
																				foreach	($payments	as	$payment)	{
																								$totalDue	-=	floatval($payment->amount);
																				}
																}
																?>
                <h5>$<?php	echo	substr(money_format("%i",	(float)	$total),	0,	-4);	?></h5>
                <h5>$<?php	echo	substr(money_format("%i",	(float)	$totalDue),	0,	-4);	?></h5>
            </div>
        </div>
    </div>
</div>