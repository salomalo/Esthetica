<?php
if	(!$user->isLoggedIn())	{
				Redirect::to('login');
}

if	($user->data()->facebook_id	==	NULL)	{
				// male
				if	($user->data()->gender	==	1)	{
								$imageURL	=	"https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/yL/r/HsTZSDw4avx.gif";
				}
				// female
				else	{
								$imageURL	=	"https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/yp/r/yDnr5YfbJCH.gif";
				}
}	else	{
				$imageURL	=	"https://graph.facebook.com/"	.	$user->data()->facebook_id	.	"/picture";
}

$token	=	Token::generate();

$rebate	=	json_decode($user->data()->clientGroupData)->rebate;

$employee	=	new	Employee(intval($user->data()->estheticienId));
?>
<script type="text/javascript">
				$(document).ready(function (e) {
								$(document).attr('title', 'Mon compte - Esthética');

								$('a.cancel-btn[data-toggle="confirmation"]').confirmation({
												title: "Êtes-vous certain<?php	echo	($user->data()->gender	==	1	?	''	:	'e');	?>?",
												container: 'body'
								});
				});
</script>
<div class="col-md-12">
    <h1>Mon compte <small>Gestion de clientèle</small></h1>
    <div class="list-group col-md-3">
								<?php
								include	'back-sidebar.php';
								?>
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
																												<?php	if	($user->data()->facebook_id)	{	?>
																																<img src="<?php	echo	$imageURL;	?>" class="img-thumbnail pull-right" style="height: 60px; width: 60px; position: absolute; top: 50px; right: 25px;" />
																												<?php	}	?>
                            <h4 class="name" style="<?php	echo	$user->data()->clientGroupStyle;	?>"><?php	echo	($user->data()->gender	==	1	?	'<i class="fa fa-lg fa-male"></i>'	:	'<i class="fa fa-lg fa-female"></i>');	?> <?php	echo	escape($user->data()->firstName)	.	' '	.	escape($user->data()->lastName);	?></h4>
                            <ul class="list-unstyled stats">
                                <li><i class="fa fa-link"></i> Type de connexion:</li>
                                <li class="data"><?php
																																				echo	($user->data()->facebook_id	!=	NULL)	?	'
												<td align="left" valign="top"><i class="fa fa-fw fa-lg fa-facebook"></i> Facebook <a href="index.php?action=update" class="btn btn-primary btn-xs">Synchroniser</a></td>
'	:	'<td align="left" valign="top"><i class="fa fa-fw fa-lg fa-asterisk"></i> Mot de passe</td>';
																																				?></li>
                                <li><i class="fa fa-fw fa-phone"></i> Numéro de téléphone:</li>
                                <li class="data"><?php	echo	escape(preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/",	"($1) $2-$3",	$user->data()->phone));	?></li>
                                <li><span class="fa fa-fw fa-inbox"></span> Courriel:</li>
                                <li class="data"><a href="mailto:<?php	echo	escape($user->data()->email);	?>"><?php	echo	escape($user->data()->email);	?></a></li>
                                <li><span class="fa fa-fw fa-user"></span> Nom d'utilisateur:</li>
                                <li class="data"><?php
																																				if	($user->data()->facebook_id)	{
																																								echo	'<em>Aucun</em>';
																																				}	else	{
																																								echo	escape($user->data()->username);
																																				}
																																				?></li>
                                <li><span class="fa fa-fw fa-asterisk"></span> Mot de passe:</li>
                                <li class="data"><?php
																																				if	($user->data()->facebook_id)	{
																																								echo	'<em>Aucun</em>';
																																				}	else	{
																																								echo	'********* <a href="index.php?action=update" class="btn btn-primary btn-xs">Modifier</a>';
																																				}
																																				?></li>
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
                                <li><span class="fa fa-fw fa-shopping-cart"></span> Mes factures:</li>
                                <li class="data"><a href="index.php?action=invoices"><?php	echo	$invoicesCount;	?> facture<?php	if	($invoicesCount	>	1)	echo	's';	?> impayée<?php	if	($invoicesCount	>	1)	echo	's';	?></a></li>
                                <li><span class="fa fa-fw fa-dollar"></span> Balance de compte:</li>
																																<?php
																																$creditsTextClass	=	"";
																																if	($creditsAmount	>	0)	{
																																				$creditsTextClass	=	"text-success";
																																}
																																?>
                                <li class="data"><span class="<?php	echo	$creditsTextClass;	?>">$<?php	echo	substr(money_format("%i",	$creditsAmount),	0,	-4);	?> disponible</span>
																																				<?php
																																				if	($rebate	>	0)	{
																																								echo	' / Rabais de '	.	$rebate	.	'% sur les achats';
																																				}
																																				?>
                                </li>
                                <li><span class="fa fa-fw fa-eye"></span> Votre esthéticien(ne):</li>
                                <li class="data"><a href="mailto:<?php	echo	escape($employee->data()->email);	?>"><?php	echo	($employee->data()->gender	==	1	?	'<i class="fa fa-lg fa-male"></i>'	:	'<i class="fa fa-lg fa-female"></i>');	?> <?php	echo	escape($employee->data()->firstName)	.	' '	.	escape($employee->data()->lastName);	?></a></li>
                                <li><span class="fa fa-fw fa-calendar"></span> Votre prochain rendez-vous:</li>
																																<?php
																																if	(isset($rendezvousResults[0]))	{
																																				echo	'<li class="data"><a href="index.php?action=myrendezvous">'	.	ucfirst(utf8_encode(strftime("%c",	strtotime($rendezvousResults[0]->startDate))))	.	'</a> <a href="index.php?action=rendezvous&id='	.	$rendezvousResults[0]->id	.	'&edit=1&token='	.	$token	.	'" class="btn btn-primary btn-xs">Modifier</a> <a href="index.php?action=rendezvous&id='	.	$rendezvousResults[0]->id	.	'&cancel=1&token='	.	$token	.	'" class="btn btn-danger btn-xs cancel-btn" data-toggle="confirmation">Annuler</a></li>';
																																}	else	{
																																				echo	'<li	class	=	"data">Aucun	rendez-vous	<a	href	=	"index.php?action=rendezvous"	class	=	"btn btn-primary btn-xs">Prendre	rendez-vous</a></li>';
																																}
																																?>
                                <li><span class="fa fa-fw fa-barcode"></span> Votre code client:</li>
                                <li class="data"><p class="small">Faites scanner ce code pour faciliter l'accès	à	votre	compte.</p></li>
																																<li	style	=	"margin-top: -20px;"><img	src	=	"http://www.barcodesinc.com/generator/image.php?code=<?php	echo	$user->data()->phone;	?>%20&style=68&type=C128B&width=200&height=50&xres=1&font=3"	class	=	"img-responsive barcode"	/></li>
																												</ul>
																												<br	class	=	"clearfix"	/>
																								</div>
																				</div>
																</div>
												</div>
												<div	class	=	"row">
																<div	class	=	"col-md-12">
																				<div	class	=	"panel panel-primary">
																								<div	class	=	"panel-heading">
																												<h3	class	=	"panel-title">ACTIONS	DE	COMPTE</h3>
																								</div>
																								<div	class	=	"panel-body">
																												<div	class	=	"row">
																																<div	class	=	"col-md-4 col-md-offset-2">
																																				<a	class	=	"btn btn-primary btn-block"	href	=	"index.php?action=update"><span	class	=	"fa fa-fw fa-edit"></span>	Modifier</a>
																																</div>
																																<div	class	=	"col-md-4">
																																				<a	class	=	"btn btn-danger btn-block"	href	=	"index.php?action=deactivate"><span	class	=	"fa fa-fw fa-ban"></span>	Désactiver	mon	compte</a>
																																</div>
																												</div>
																								</div>
																				</div>
																</div>
												</div>
								</div>
				</div>
</div>