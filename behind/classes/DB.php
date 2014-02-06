<?php
class DB {
	private static $_instance = null;
	private $_pdo, 
			$_query, 
			$_error = false, 
			$_count = 0;
	public	$_results;
			
	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db') . ';charset=utf8', Config::get('mysql/username'), Config::get('mysql/password'));
		} catch(PDOException $e) {
			die($e->getMessage());
		}
	}
	
	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	
	public function query($sql, $params = array()) {
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			if(count($params)) {
				$x = 1;
				foreach($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			
			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			}
			else {
				$this->_error = true;
			}
		}
		
		return $this;
	}
	
	private function action($action, $table, $where = array(), $orderby = '') {
		if(count($where) === 3) {
			$operators = array('=', '>', '<', '>=', '<=');
			
			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];
			
			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? {$orderby}";
				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}
		return false;
	}
	
	public function get($table, $where = array(), $data = '*', $orderby = '') {
		return $this->action('SELECT ' . $data, $table, $where, $orderby);
	}
	
	public function delete($table, $where = array()) {
		return $this->action('DELETE', $table, $where);
	}
	
	public function insert($table, $fields = array()) {
		if(count($fields)) {
			$keys 	= array_keys($fields);
			$values	= null;
			$x 		= 1;
			
			foreach($fields as $field) {
				$values .= '?';
				if($x < count($fields)) {
					$values .= ', ';
				}
				$x++;
			}
			
			$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
			
			if(!$this->query($sql, $fields)->error()) {
				return true;
			}
		}
		return false;
	}
	
	public function update($table, $newData = array(), $where = array()) {
		if(count($where) === 3) {
			$where2	= '';
			$set 	= '';
			$x		= 1;
			
			foreach($newData as $name => $value) {
				$set .= "{$name} = ?";			
				if($x < count($newData)) {
					$set .= ', ';
				}
				$x++;
			}
			$operators = array('=', '>', '<', '>=', '<=');
			
			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];
			
			if(!in_array($operator, $operators)) {
				return false;
			}
		
			$sql = "UPDATE {$table} SET {$set} WHERE {$field} {$operator} ?";
			// Add WHERE value at the end.
			$newData[] = $value;
			if(!$this->query($sql, $newData)->error()) {
				return $this;
			}
		}
		return false;
	}
	
	public function results() {
		return $this->_results;
	}
	
	public function first() {
		return $this->_results[0];
	}
	
	public function last() {
		return $this->_results[$this->_count - 1];
	}
	
	public function error() {
		return $this->_error;
	}
	
	public function count() {
		return $this->_count;
	}
}