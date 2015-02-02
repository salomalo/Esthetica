<?php
header('Content-Type: text/html; charset=utf-8');

if	(!$user->isLoggedIn())	{
				Redirect::to('login');
}
?>

<script type="text/javascript">
				$(document).ready(function (e) {
								$(document).attr('title', 'Mes factures - Esthética');
				});
</script>
<div class="col-md-12">
    <h1>Mon compte <small>Gestion de clientèle</small></h1>
    <div class="list-group col-md-3">
								<?php
								include	'back-sidebar.php';

								if	($invoices->exists())	{
												foreach	($invoicesResults	as	$key	=>	$invoice)	{
																if	($invoice->status	==	"Payée")	{
																				$invoicesResults[$key]->class	=	'success';
																				$invoicesResults[$key]->icon	=	'check';
																}	else	if	($invoice->status	==	"Impayée")	{
																				$invoicesResults[$key]->class	=	'warning';
																				$invoicesResults[$key]->icon	=	'dollar';
																}	else	{
																				$invoicesResults[$key]->class	=	'danger';
																				$invoicesResults[$key]->icon	=	'times';
																}
												}
								}
								?>
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
                        <th style="width: 20%;" class="text-right">Montant total</th>
                        <th style="width: 25%;">Options</th>
                    </tr>
                </thead>
                <tbody>
																				<?php
																				if	(!$invoices->exists())	{
																								echo	'
								<tr>
									<td colspan="5">Aucune facture dans votre compte.</td>
								</tr>';
																				}	else	{
																								foreach	($invoicesResults	as	$invoice)	{
																												echo	'
								<tr class="'	.	$invoice->class	.	'">
									<td><strong><i class="fa fa-fw fa-file-o"></i> '	.	$invoice->id	.	'</strong></td>
									<td>'	.	substr($invoice->date,	0,	-9)	.	'</td>
									<td><i class="fa fa-fw fa-'	.	$invoice->icon	.	'"></i> '	.	$invoice->status	.	'</td>
									<td class="text-right">$'	.	substr(money_format("%i",	(float)	$invoice->total),	0,	-4)	.	'</td>
									<td><div class="btn-group btn-group-justified">
										<a class="btn btn-sm btn-primary" href="index.php?action=invoice&id='	.	$invoice->id	.	'"><i class="fa fa-fw fa-eye"></i> Voir</a> ';
																												if	($invoice->status	==	"Impayée")	{
																																echo	'<a href="index.php?action=invoice&id='	.	$invoice->id	.	'&pay=1" class="btn btn-sm btn-default"><i class="fa fa-fw fa-dollar"></i> Payer</a></td>';
																												}	else	{
																																echo	'<a class="btn btn-sm btn-default disabled"><i class="fa fa-fw fa-dollar"></i> Payer</a>';
																												}
																												echo	'
									</div></td>
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
                        <td class="success"><i class="fa fa-fw fa-check"></i> Payée</td>
                        <td class="warning"><i class="fa fa-fw fa-dollar"></i> Impayée</td>
                        <td class="danger"><i class="fa fa-fw fa-times"></i> Annulée</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>