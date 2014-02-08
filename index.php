<?php
require_once 'behind/core/init.php';

// =-------------------------------=

$param = @$_GET['action'];

$actions = array(
	'' => 'accueil.php',
	'accueil' => 'accueil.php',
	'login' => 'login.php',
	'logout' => 'logout.php',
	'register' => 'register.php',
	'register2' => 'register2.php',
	'register3' => 'register3.php',
	'register4' => 'register4.php',
	'myaccount' => 'myaccount.php',
	'update' => 'update.php',
	'rendezvous' => 'rendezvous.php',
	'invoice' => 'invoice.php',
	'invoices' => 'invoices.php',
	'credits' => 'credits.php',
	'services' => 'services.php',
	'apropos' => 'apropos.php',
	'facebookCreate' => 'facebookCreate.php',
	'facebookCreate2' => 'facebookCreate2.php',
	'facebookLogin' => 'facebookLogin.php',
	'contacteznous' => 'contacteznous.php');

if(isset($actions[$param])) {
	$action = $actions[$param];
}
else {
	Session::flash('flash', array('status' => 'error', 'message' => '<strong>Erreur de requête!</strong> La page demandée (' . escape($param) . ') n\'existe pas ou une erreur s\'est produite lors de la redirection.'));
	header("Location: index.php?action=accueil");
}

include('behind/includes/core/header.php');
include('behind/includes/' . $action);
include('behind/includes/core/footer.php');