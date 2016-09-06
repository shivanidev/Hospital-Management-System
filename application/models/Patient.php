<?php

class Application_Model_Patient extends Zend_Db_Table_Abstract {

    /**
     * table name
     * @var string
     */
    protected $_name = 'patient';

    /**
     * table primary key (AI)
     * table filed - id 
     * @var int 
     */
    protected $_id;

    /**
     * table filed - patient_id
     * @var int
     */
    protected $_patientId;

    /**
     * table filed - description
     * @var string
     */
    protected $_note;

    /**
     * table filed - create_by
     * @var float
     */
    protected $_changedBy;

    /**
     * table filed - create_on
     * @var string
     */
    protected $_date;

    /**
     * table filed - type
     * 1 - doctor channeling
     * 2 - labtest booking
     * 3 - checking booking
     * @var int
     */
    protected $_type;

    /**
     * table filed - status
     * @var tinyint
     */
    protected $_status;

    /**
     * table filed - change_by
     * @var date
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    /**
     * Use for add details for docotr
     * @return int 
     */
    public function addPatient() {
        $data = array(
            'patient_id' => $this->_patientId,
            'type' => $this->_type
        );

        return $this->insert($data);
    }

    /**
     * Use for add note to patient after check user
     * @return mixed
     *      no of rows for successful, false for unsuccessful
     */
    public function updatePatient() {
        $data = array(
            'note' => $this->_note,
            'date' => $this->_date,
            'change_by' => $this->_changedBy,
            'status' => $this->_status
        );

        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);
    }

    //geting data in patient table for 
    public function getPatients($tbType, $patientId) {
        $select = $this->select()
                ->from(
                        array('a' => 'patient'), array(
                    'id',
                    'change_by',
                    'note',
                    'date'
                        )
                )
                ->where('type =?', $tbType)
                ->where('patient_id =?', $patientId)
                ->setIntegrityCheck(false);

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    //geting descrption in patinet table
    public function getPatient() {
        $select = $this->select()
                ->from(
                        array('a' => 'patient'), array(
                    'patient_id',
                    'note',
                    'change_by',
                    'type',
                    'status',
                    'date'
                        )
                )
                ->where('a.id =?', $this->_id)
                ->setIntegrityCheck(false);

        $result = $this->fetchRow($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

}