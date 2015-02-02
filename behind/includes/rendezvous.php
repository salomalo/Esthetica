<?php

if	(Input::exists('get'))	{	// Most likely landed on page via myrendezvous.php links

				/**
					* Let's store our RDV ID, we'll need it in both cases.
					* BETTER YET! Instanciate the RendezVous class!
					*/
				if	(Input::get('id'))	{
								/**
									* Better be safe, all these actions require us to be identified
									*/
								if	(!$user->isLoggedIn())	{
												Redirect::to('login');
								}
								$currentRendezvousEdition	=	RendezVous::find(intval(Input::get('id')));

								if	(!$currentRendezvousEdition->exists())	{
												Session::flash('flash',	array('status'	=>	'error',	'message'	=>	'<strong>Erreur!</strong> Attention, le rendez-vous sélectionné n\'existe pas.'));
												Redirect::to('myrendezvous');
								}

								if	(strtotime($currentRendezvousEdition->data()->startDate)	<	time())	{
												Session::flash('flash',	array('status'	=>	'error',	'message'	=>	'<strong>Erreur!</strong> Attention, le rendez-vous sélectionné est déjà passé.'));
												Redirect::to('myrendezvous');
								}

								/**
									* Are we looking to cancel?
									*/
								if	(Input::get('cancel'))	{
												if	(Token::check(Input::get('token')))	{
																$time	=	$currentRendezvousEdition->data()->startDate;
																$currentRendezvousEdition->cancel();
																Session::flash('flash',	array('status'	=>	'success',	'message'	=>	'<strong>Rendez-vous annulé!</strong> Vous avez bel et bien annulé votre rendez-vous de '	.	utf8_encode(strftime('%c',	strtotime($time))	.	'.')));
												}
												Redirect::to('myrendezvous');
								}

								/**
									* Or should we actually edit the time?
									*/
								if	(Input::get('edit'))	{
												/**
													* Show the modify RDV page
													*/
												include('includes/editrendezvous.php');
								}
				}
				/**
					* There is no associated rendez-vous with the request.. assume we need a new one?
					*/	else	{
								include('includes/newrendezvous.php');
				}
}
/**
	* Nothing in _REQUEST.. let's assume again this is a new rendez-vous.
	*/	else	{
				include('includes/newrendezvous.php');
}