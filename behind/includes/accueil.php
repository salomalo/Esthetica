<?php

if(Session::exists('flash')) {
	$flash = Session::flash('flash');
	switch($flash['status']) {
		case 'success':
			$css = 'alert alert-success';
			break;
		case 'error':
			$css = 'alert alert-danger';
			break;
		case 'warning':
			$css = 'alert alert-warning';
			break;
		case 'info':
			$css = 'alert alert-info';
			break;
	}
	
	$flash['status'] = $css;
	unset($css);
	echo '<div class="' . $flash['status'] . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $flash['message'] . '</div>';
}
?>
<div class="jumbotron">
            <h1>Parrainage</h1>
            <p class="lead">Lorsque vous référez nos services à vos amis, nous nous assurons de vous offrir un cadeau à utiliser chez Ongles Trycia. Vous aurez droit à une note de crédit de 25$<sup>*</sup> en référant un nouveau client.</p>
            <p><small>* Note de crédit appliquée au compte.</small></p>
            <p><a class="btn btn-lg btn-success" href="index.php?action=myaccount" role="button"><span class="glyphicon glyphicon-gift"></span> Prendre votre rendez-vous</a></p>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <h2>Service courtois</h2>
                <p>Chez Trycia, nous nous sommes donné comme mission de prioriser le service au client, vous. Vous pouvez nous faire confiance puisque vous êtes la raison pour laquelle nous existons.</p>
                	</div>
            <div class="col-xs-4">
                <h2>Garantie</h2>
                <p>Si un problème survient avec vos ongles, nous nous engagons à vous les réparer sans aucun frais additionnel et ce, pendant toute la durée de vie normale du produit.</p>
            </div>
            <div class="col-xs-4">
                <h2>Produits de qualité</h2>
                <p>Tous nos produits sont faits d'éléments de qualité. Nous ne voulons pas de basse qualité pour nous &mdash; vous aurez de la haute qualité aussi.</p>
            </div>
        </div>