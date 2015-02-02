</div>
</div>
<footer>
    <div class="container">
        <p><?php
												if	($copyright_show)	{
																echo	'
				Copyright © Ongles Trycia inc. 2013-'	.	date("Y")	.	' &mdash; Tous droits réservés.';
												}
												?>

        </p><?php
								if	($dev)	{
												echo	'
			<div class="panel panel-info center-block text-center panel-license">
				<div class="panel-heading">License de développement</div>
				<div class="panel-body">
					Ce message s\'affiche uniquement à cause de l\'utilisation de la licence DÉVELOPPEMENT. Produit enregistré à '	.	$Licensing->results['companyname']	.	' par '	.	$Licensing->results['registeredname']	.	' ['	.	$Licensing->results['email']	.	']. En changeant la licence pour celle "LIVE", (ne commençant pas par M7TRS-DEV), ce message disparraîtra.
				</div>
			</div>
';
								}
								?>
    </div>
</footer>
</body>
</html>