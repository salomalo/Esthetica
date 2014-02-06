<?php

$javascript = '';

$user = new User();

if($user->isLoggedIn()) {
	
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validation = new Validation();
		
		$validation->check($_POST, array(
			'inputPhoneNumber' => array(
				'required' => true,
				'regex' => "/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/",
				'fieldName' => 'Numéro de téléphone',
				'unique' => 'users',
				'fieldUnique' => 'phone',
				'group' => 'phoneNumberGroup'
			)
		));
		
		if($validation->passed()) {
			// Validation passed - up to second step now.
			Session::put('phone', Input::get('inputPhoneNumber'));
			$user->create(array(
				'username'	=> '',
				'password'	=> '',
				'salt'		=> '',
				'phone'		=> preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", Session::get('phone')),
				'gender'	=> Session::get('gender'),
				'firstName'	=> Session::get('first_name'),
				'lastName'	=> Session::get('last_name'),
				'email'		=> Session::get('email'),
				'joined'	=> date('Y-m-d H:i:s'),
				'facebook_id'=> Session::get('facebook_id'),
				'clientGroup'=> 1
			));	
			/*$update = DB::update('users', array('facebook_id' => ''), array('id', '=', $user->data()->id));
			if($update) {
				echo 'SUCCESSFULLY MODIFIED USER ACCOUNT FOR FACEBOOK';
			}*/
		}
		else {
			$errors = '<div class="alert alert-danger"><strong>Oups!</strong> Veuillez corriger les problèmes suivants:<br />';
			foreach($validation->errors() as $key) {
				$errors .= $key['message'] . '<br />';
				$javascript .= '$(\'#' . $key['group'] . '\').addClass(\'has-error\').delay(500).effect("bounce"); ';
			}
			$errors .= '</div>';
		}
	}
	else {
		$errors = '<div class="alert alert-danger"><strong>Oups!</strong> Assurez-vous de soumettre le formulaire avec le bouton Continuer.</div>';
	}
}