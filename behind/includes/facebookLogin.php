<?php
require_once('behind/classes/facebook/facebook.php');

$javascript = '';
$config = array(
	'appId' => '1388507124730191',
	'secret' => '115ad244dcc4d602ee6205d2cce175f7',
	'allowSignedRequest' => false // optional but should be set to false for non-canvas apps
);
$facebook = new Facebook($config);
$user_id = $facebook->getUser();

if($user_id) {
	// We have a user ID, so probably a logged in user.
	// If not, we'll get an exception, which we handle below.
	try {
		$check = new User(-1);
		echo $check->findFacebook($user_id);
		if($check->exists()) {
			$check->login();
			Session::flash('flash', array('status' => 'success', 'message' => '<strong>Succès!</strong> Bienvenue ' . $check->data()->firstName . ', vous vous êtes connecté à votre compte avec succès.'));
			Redirect::to('accueil');
		}
		else {
			Redirect::to('facebookCreate');
		}
	}
	catch(FacebookApiException $e) {
		// If the user is logged out, you can have a 
		// user ID even though the access token is invalid.
		// In this case, we'll get an exception, so we'll
		// just ask the user to login again here.
		$login_url = $facebook->getLoginUrl(array('scope' => 'email')); 
		Redirect::outter($login_url);
	}
}
else {
	// No user, print a link for the user to login
	$login_url = $facebook->getLoginUrl(array('scope' => 'email'));
	Redirect::outter($login_url);
}