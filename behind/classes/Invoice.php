<?php

class	Invoice	{

				private	$_db,
												$_data;

				public	function	__construct($invoice	=	null)	{
								$this->_db	=	DB::getInstance();

								if	($invoice)	{
												$this->find($invoice);
								}
				}

				public	function	create($fields	=	array())	{
								$invoiceId	=	$this->_db->insert('invoice',	$fields);
								if	(!$invoiceId)	{
												throw	new	Exception('Un problème est survenu lors de la création de la facture.');
								}

								return	$invoiceId;
				}

				public	function	find($invoice	=	null)	{
								if	($invoice)	{
												$field	=	'id';
												$data	=	$this->_db->get('invoices',	array($field,	'=',	$invoice));

												if	($data->count())	{
																$this->_data	=	$data->first();
																return	true;
												}
								}
								return	false;
				}

				public	function	findAll($userId	=	null)	{
								if	($userId)	{
												$field	=	'user_id';
												$data	=	$this->_db->get('invoices',	array($field,	'=',	$userId),	'*',	'ORDER BY date DESC');

												if	($data->count())	{
																$this->_data	=	$data;
																return	true;
												}
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
								return	$this->_data	=	$data;
				}

				public	function	markPaid($payment)	{
								if	($this->exists())	{
												return	$this->_db->update('invoices',	array('status'	=>	'Payée',	'payments'	=>	json_encode($payment)),	array('id',	'=',	$this->_data->id));
								}
								return	false;
				}

				public	static	function	markPaidWithId($id,	$payment)	{
								return	DB::getInstance()->update('invoices',	array('status'	=>	'Payée',	'payments'	=>	json_encode($payment)),	array('id',	'=',	$id));
				}

				public	function	markUnpaid($clearpayments	=	false)	{
								if	($this->exists())	{
												if	($clearpayments)	{
																return	DB::getInstance()->update('invoices',	array('status'	=>	'Impayée',	'payments'	=>	json_encode(array())),	array('id',	'=',	$id));
												}
												return	$this->_db->update('invoices',	array('status'	=>	'Impayée'),	array('id',	'=',	$this->_data->id));
								}
								return	false;
				}

				public	static	function	markUnpaidWithId($id,	$clearpayments	=	false)	{
								if	($clearpayments)	{
												return	DB::getInstance()->update('invoices',	array('status'	=>	'Impayée',	'payments'	=>	json_encode(array())),	array('id',	'=',	$id));
								}

								return	DB::getInstance()->update('invoices',	array('status'	=>	'Impayée'),	array('id',	'=',	$id));
				}

				public	function	cancel()	{
								if	($this->exists())	{
												return	$this->_db->update('invoices',	array('status'	=>	'Annulée'),	array('id',	'=',	$this->_data->id));
								}
								return	false;
				}

				public	static	function	total($invoice,	$user	=	null)	{
								$data	=	json_decode($invoice->data);
								$total	=	0;
								foreach	($data	as	$line)	{
												$total	+=	$line->qty	*	$line->price;
								}

								if	($user)	{
												$rebate	=	intval(json_decode($user->data()->clientGroupData)->rebate);
												$rebate	=	1	-	($rebate	/	100);
												$total	=	$total	*	$rebate;
								}

								$taxes	=	json_decode($invoice->taxes);
								$taxesAmount	=	0.0;
								foreach	($taxes	as	$tax)	{
												$temp	=	round($total	*	(	(float)	$tax->percentage	/	100	),	2);
												$taxesAmount	+=	(float)	$temp;
								}
								$total	+=	(float)	$taxesAmount;
								return	($total	<	0)	?	0	:	round($total,	2);
				}

				public	static	function	totalDue($invoice,	$user	=	null)	{
								$total	=	self::total($invoice,	$user);

								$payments	=	json_decode($invoice->payments);
								if	($payments)	{
												foreach	($payments	as	$payment)	{
																$total	-=	$payment->amount;
												}
								}
								return	($total	<	0)	?	0	:	round($total,	2);
				}

}
