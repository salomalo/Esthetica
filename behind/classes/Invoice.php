<?php

class Invoice {
	private $_db,
			$_data;
	
	public function __construct($invoice = null) {
		$this->_db = DB::getInstance();
		
		if($invoice) {
			$this->find($invoice);
		}
	}
	
	public function create($fields = array()) {
		$invoiceId = $this->_db->insert('invoice', $fields);
		if(!$invoiceId) {
			throw new Exception('Un problème est survenu lors de la création de la facture.');
		}
		
		return $invoiceId;
	}
	
	public function find($invoice = null) {
		if($invoice) {
			$field = 'id';
			$data = $this->_db->get('invoices', array($field, '=', $invoice));
			
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
			$data = $this->_db->get('invoices', array($field, '=', $userId), '*', 'ORDER BY date DESC');
			
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
	
	public function markPaid() {
		if($this->exists()) {
			return $this->_db->update('invoices', array('status' => 'Payée'), array('id', '=', $this->_data->id));
		}
		return false;
	}
	
	public function markUnpaid() {
		if($this->exists()) {
			return $this->_db->update('invoices', array('status' => 'Impayée'), array('id', '=', $this->_data->id));
		}
		return false;
	}
	
	public function Cancel() {
		if($this->exists()) {
			return $this->_db->update('invoices', array('status' => 'Annulée'), array('id', '=', $this->_data->id));
		}
		return false;
	}
	
	public static function total($invoice) {
		$data = unserialize($invoice->data);
		$total = 0;
		foreach($data as $line) {
			$total += $line['qty']*$line['price'];
		}
		$total -= (float)$invoice->credit;
		$taxes = unserialize($invoice->taxes);
		foreach($taxes as $tax) {
			$total += (float)$tax['total'];
		}
		return $total;
	}
}