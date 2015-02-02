<?php

class	RendezVous	{

				private	$_db,
												$_data;

				/**
					* Instanciation de la classe de rendez-vous
					* @param int $rdv L'id du rendez-vous en question, ou null.
					*/
				public	function	__construct($rdv	=	null)	{
								$this->_db	=	DB::getInstance();

								if	($rdv)	{
												$data	=	$this->_db->get('rendezvous',	array('id',	'=',	$rdv));

												if	($data->count())	{
																$this->_data	=	$data->first();
												}
								}
				}

				/**
					* Crée un rendez-vous avec les détails suivants
					* @param array $fields Liste associative de tout les champs à remplir dans la base de donnée.
					* @return RendezVous Retourne la valeur instanciée du nouveau rendez-vous.
					* @throws Exception Erreur de DB -> rare
					*/
				public	static	function	create($fields	=	array())	{
								$rdvId	=	DB::getInstance()->insert('rendezvous',	$fields);
								if	(!$rdvId)	{
												throw	new	Exception('Un problème est survenu lors de la création du rendez-vous.');
								}

								return	self::find($rdvId);
				}

				public	static	function	find($rdv	=	null)	{
								if	($rdv)	{
												$field	=	'id';
												$data	=	DB::getInstance()->get('rendezvous',	array($field,	'=',	$rdv));

												if	($data->count())	{
																$obj	=	new	RendezVous();
																$obj->setData($data->first());
																return	$obj;
												}
												return	new	RendezVous();
								}
								return	false;
				}

				/**
					*
					* @param type $userId
					* @return RendezVous[]|boolean
					*/
				public	static	function	findAll($userId	=	null)	{
								if	($userId)	{
												$field	=	'r.user_id';
												$data	=	DB::getInstance()->get('rendezvous r',	array($field,	'=',	$userId),	'r.*, e.firstName, e.lastName, e.gender, e.email',	'ORDER BY startDate DESC',	'INNER JOIN employees e ON r.employeeId = e.id');

												if	($data->count())	{
																$obj	=	new	RendezVous();
																$obj->setData($data);
																return	$obj;
												}
												return	new	RendezVous();
								}
								return	false;
				}

				public	function	exists()	{
								return	!empty($this->_data);
				}

				public	function	data()	{
								return	$this->_data;
				}

				public	function	setData($data)	{
								$this->_data	=	$data;
				}

				public	function	cancel()	{
								return	$this->_db->delete('rendezvous',	array('id',	'=',	$this->_data->id));
				}

				public	function	changeServices($newServices)	{
								return	$this->_db->update('rendezvous',	array('services'	=>	json_encode($newServices),	'confirmed'	=>	0),	array('id',	'=',	$this->_data->id));
				}

				public	function	changeDate($newDate)	{
								return	$this->_db->update('rendezvous',	array('startDate'	=>	$newDate,	'confirmed'	=>	0),	array('id',	'=',	$this->_data->id));
				}

				public	function	notifyClient()	{
								// TODO: Send emails
				}

}
