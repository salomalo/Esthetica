
        <div class="footer">
        	<p><?php

if($copyright_show) { echo 'Copyright © Ongles Trycia inc. 2013-' . date("Y") . ' &mdash; Tous droits réservés. 
Créé avec l\'utilisation de <a href="http://getbootstrap.com/" target="_blank">Twitter Bootstrap 3</a>. Thème par <a href="http://bootswatch.com/" target="_blank">Bootswatch</a>.'; }
if($dev) { echo '<div class="alert alert-info">Ce message s\'affiche uniquement à cause de l\'utilisation de la licence DÉVELOPPEMENT. Produit enregistré à ' . $Licensing->results['companyname'] . ' par ' . $Licensing->results['registeredname'] . ' [' . $Licensing->results['email'] . ']. En changeant la licence pour celle "LIVE", (ne commençant pas par M7TRS-DEV), ce message disparraîtra.</div>'; }
?></p>
        </div>
    </div>
</body>
</html>