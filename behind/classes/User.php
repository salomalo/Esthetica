<?php
class User {
	private $_db,
			$_data,
			$_isLoggedIn;
			
	private static $_sessionName = 'user';
	private static $_cookieName = 'hash';
	
	public function __construct($user = null) {
		$this->_db = DB::getInstance();
		
		if(!$user) {
			if(Session::exists(self::$_sessionName)) {
				$user = Session::get(self::$_sessionName);
				
				if($this->find($user)) {
					if($this->data()->status === "Banni") {
						return false;
					}
					$this->_isLoggedIn = true;
				}
				else {
					// Process logout
				}
			}
		}
		else if($user !== -1) {
			$this->find($user);
		}
	}
	
	public function create($fields = array()) {
		$userId = $this->_db->insert('users', $fields);
		if(!$userId) {
			throw new Exception('Un problème est survenu lors de la création du compte.');
		}
		
		return $userId;
	}
	
	public function update($fields = array()) {
		echo $this->_data->id;
		return $this->_db->update('users', $fields, array('id', '=', $this->_data->id));;
	}
	
	public function find($user = null) {
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));
			
			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
	
	public function findPhone($phone = '') {
		if($phone) {
			$data = $this->_db->get('users', array('phone', '=', $phone));
			
			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
	
	public function findFacebook($facebook_id = '') {
		if($facebook_id) {
			$data = $this->_db->get('users', array('facebook_id', '=', $facebook_id));
			
			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
	
	public function login($username = null, $password = null, $remember = false) {		
		if(!$username && !$password && $this->exists()) {
			Session::put(self::$_sessionName, $this->data()->id);
			$this->_isLoggedIn = true;	
			return true;
		}
		else {			
			$user = $this->find($username);
			
			if($user) {
				if($this->data()->status === "Banni") {
					return false;
				}
				if($this->data()->password === Hash::make($password, $this->data()->salt)) {
					Session::put(self::$_sessionName, $this->data()->id);
					$this->_isLoggedIn = true;
					
					if($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));
						
						if(!$hashCheck->count()) {
							$this->_db->insert('users_session', array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
						}
						else {
							$hash = $hashCheck->first()->hash;
						}
						
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}
					return true;
				}
			}		
		}
		return false;
	}
	
	public function exists() {
		return !empty($this->_data);
	}
	
	public function logout() {
		
		$this->_db->delete('users_session');
		
		Session::delete(self::$_sessionName);
		Cookie::delete($this->_cookieName);
		
		Redirect::to('accueil');
	}
	
	public function data() {
		return $this->_data;
	}
	
	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}
}