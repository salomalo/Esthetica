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
			<p class="lead">Lorsque vous référez nos services à vos amis, vous aurez droit à une carte cadeau de 25$<sup>*</sup> puisque vous nous référez un nouveau client!</p>
			<p><small>* Note de crédit appliquée au compte.</small></p>
			<p class="hidden-xs hidden-sm"><a class="btn btn-lg btn-success" href="index.php?action=myrdv" role="button"><span class="glyphicon glyphicon-gift"></span> Prendre rendez-vous</a></p>
			<p class="hidden-md hidden-lg"><a class="btn btn-lg btn-success" href="index.php?action=myrdv" role="button"><span class="glyphicon glyphicon-gift"></span> Prendre rendez-vous</a></p>
		</div>
		<div class="row">
			<div class="col-xs-6 col-md-4">
				<div class="circle center-block" style="color: #D81316;"><span class="glyphicon glyphicon-heart"></span></div>
				<h2 style="color: #D81316;">Service à la clientèle</h2>
				<p class="text-justify">Chez Ongles Trycia, nous nous sommes donné comme mission de prioriser le service au client, vous. Vous pouvez nous faire confiance puisque vous êtes la raison pour laquelle nous existons.</p>
			</div>
			<div class="col-xs-6 col-md-4">
				<div class="circle center-block" style="color: #098200;"><span class="glyphicon glyphicon-leaf"></span></div>
				<h2 style="color: #098200;">Produits de qualité</h2>
				<p class="text-justify">Tous nos produits sont faits d'éléments de qualité. Nous ne voulons pas de basse qualité pour nous-mêmes &mdash; alors pourquoi vous en auriez?</p>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="circle center-block" style="color: #E5D931;"><span class="glyphicon glyphicon-certificate"></span></div>
				<h2 style="color: #E5D931;">Garantie</h2>
				<p class="text-justify">Si un problème survient avec vos ongles, nous nous engageons à vous les réparer sans aucun frais additionnel et ce, pendant toute la durée de vie normale du produit.</p>
			</div>
		</div>