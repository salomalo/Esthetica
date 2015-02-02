<?php

/*
 * Esthética
 * Including M7TRS
 * @copyright Mori7 Technologie
 */

/**
 * Description of Employee
 *
 * @author Francis Morissette
 */
class Employee {

    private $_db,
            $_data;

    public function __construct($employee = null) {
        $this->_db = DB::getInstance();
        if ($employee !== -1 || $employee !== null) {
            $this->find($employee);
        }
    }

    /**
     * Returns the employee's data
     * @param mixed $employee Employee ID or Email
     * @return boolean
     */
    public function find($employee = null) {
        if ($employee) {
            $field = (is_numeric($employee)) ? 'e.id' : 'e.email';
            $data = $this->_db->get('employees e', array($field, '=', $employee), 'e.*, et.type', '', 'INNER JOIN employeetypes et ON et.id = e.employeeType');


            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function create($fields = array()) {
        $employeeId = $this->_db->insert('employees', $fields);
        if (!$employeeId) {
            throw new Exception('Un problème est survenu lors de la création de l\'employé.');
        }

        return $employeeId;
    }

    public function update($fields = array()) {
        return $this->_db->update('employees', $fields, array('id', '=', $this->_data->id));
    }

    public function exists() {
        return !empty($this->_data);
    }

    public function data() {
        return $this->_data;
    }

}
