<?php 
class Validation {
	private	$_passed = false,
			$_errors = array(),
			$_db = null;
	
	public function __construct() {
		$this->_db = DB::getInstance();
	}
	
	public function check($source, $items = array()) {
		foreach($items as $item => $rules) {
			foreach($rules as $rule => $ruleValue) {
				$value = trim(@$source[$item]);
				
				if($rule === 'required' && empty($value)) {
					$this->addError(array('group' => $rules['group'], 'message' => 'Le champ <strong>' . $rules['fieldName'] . '</strong> est obligatoire.'));
				}
				else if(!empty($value)) {
					switch($rule) {
						case 'min':
							if(strlen($value) < $ruleValue) {
								$this->addError(array('group' => $rules['group'], 'message' => 'Le champ <strong>' . $rules['fieldName'] . '</strong> doit contenir au moins ' . $ruleValue . ' caractères. Vous avez ' . strlen($value) . ' caractères.'));
							}
						break;
						case 'max':
							if(strlen($value) > $ruleValue) {
								$this->addError(array('group' => $rules['group'], 'message' => 'Le champ <strong>' . $rules['fieldName'] . '</strong> doit contenir au plus ' . $ruleValue . ' caractères. Vous avez ' . strlen($value) . ' caractères.'));
							}
						break;
						case 'matches':
							if($value != $source[$ruleValue]) {
								$this->addError(array('group' => $rules['group'], 'message' => 'Le champ <strong>' . $rules['fieldName'] . '</strong> doit correspondre au champ <strong>' . $items[$ruleValue]['fieldName'] . '.'));
							}
						break;
						case 'unique':
							$check = $this->_db->get($ruleValue, array($rules['fieldUnique'], '=', preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", $value)));
							if($check->count()) {
								$this->addError(array('group' => $rules['group'], 'message' => 'La valeur du champ <strong>' . $rules['fieldName'] . '</strong> existe déjà.'));
							}
						break;
						case 'regex':
							if(!preg_match($ruleValue, $value)) {
								$this->addError(array('group' => $rules['group'], 'message' => 'La valeur du champ <strong>' . $rules['fieldName'] . '</strong> n\'est pas valide.'));
							}
						break;
					}
				}
			}
		}
		
		if(empty($this->_errors)) {
			$this->_passed = true;
		}
	}
	
	private function addError($error) {
		$this->_errors[] = $error;
	}
	
	public function errors() {
		return $this->_errors;
	}
	
	public function passed() {
		return $this->_passed;
	}
}