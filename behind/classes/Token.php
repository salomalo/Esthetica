<?php

class	Token	{

				private	static	$_tokenName	=	'token';

				public	static	function	generate()	{
								return	Session::put(self::$_tokenName,	md5(uniqid()));
				}

				public	static	function	check($token)	{
								if	(Session::exists(self::$_tokenName)	&&	$token	==	Session::get(self::$_tokenName))	{
												Session::delete(self::$_tokenName);
												return	true;
								}
								return	false;
				}

}
