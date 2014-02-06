<?php

class Credit {
	private $_db,
			$_data;
	
	public function __construct($credit = null) {
		$this->_db = DB::getInstance();
		
		if($credit) {
			$this->find($credit);
		}
	}
	
	public function create($fields = array()) {
		$creditId = $this->_db->insert('credits', $fields);
		if(!$creditId) {
			throw new Exception('Un problème est survenu lors de la création du crédit.');
		}
		
		return $creditId;
	}
	
	public function find($credit = null) {
		if($credit) {
			$field = 'id';
			$data = $this->_db->get('credits', array($field, '=', $credit));
			
			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
	
	public function findAll($userId = null) {
		if($userId) {
			$field = 'user_id';
			$data = $this->_db->get('credits', array($field, '=', $userId), '*', 'ORDER BY date DESC');
			
			if($data->count()) {
				$this->_data = $data;
				return true;
			}
		}
		return false;
	}
	
	public function exists() {
		return !empty($this->_data);
	}
	
	public function data() {
		return $this->_data;
	}
	
	public static function total($credit) {
		$data = unserialize($credit->data);
		$total = 0;
		foreach($data as $line) {
			$total += $line['qty']*$line['amount'];
		}
		return $total;
	}
}