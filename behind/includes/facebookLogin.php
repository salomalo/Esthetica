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
		$check->findFacebook($user_id);
		if($check->exists()) {
			if($check->data()->status === "Banni") {
				Session::flash('flash', array('status' => 'error', 'message' => '<strong>Erreur!</strong> Votre compte a été banni. Veuillez nous contacter pour plus d\'informations.'));
				Redirect::to('accueil');
			}
			$user_profile = $facebook->api('/me?fields=first_name,last_name,email,gender','GET');
			$fields['firstName'] = $user_profile['first_name'];
			$fields['lastName'] = $user_profile['last_name'];
			$fields['email'] = @$user_profile['email'];
			if($user_profile['gender'] === 'male') {
				$gender = 1;
			}
			else if($user_profile['gender'] === 'female') {
				$gender = 2;
			}
			$fields['gender'] = $gender;
			$check->update($fields);
			$login = $check->login();
			if($login) {				
				$check->update(array('status' => 'Actif'));
				if(Input::get('fromUpdate') == 1) {				
					Session::flash('flash', array('status' => 'success', 'message' => '<strong>Succès!</strong> Votre compte a bel et bien été resynchronisé!'));
					Redirect::to('update');
				}
				Session::flash('flash', array('status' => 'success', 'message' => '<strong>Succès!</strong> Bienvenue ' . $fields['firstName'] . ', vous vous êtes connecté à votre compte avec succès. <a href="index.php?action=myaccount" class="alert-link">Voir mon compte.</a>'));
				Redirect::to('accueil');
			}
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