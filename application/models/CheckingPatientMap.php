<?php

class Application_Model_CheckingPatientMap extends Zend_Db_Table_Abstract {

    /**
     * table name
     * @var string
     */
    protected $_name = 'checking_patient_map';
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
     * table filed - channel_date
     * @var date
     */
    protected $_checkingId;
    /**
     * table filed - date
     * @var date
     */
    protected $_date;
    /**
     * table filed - comment
     * @var string
     */
    protected $_comment;
    /**
     * table filed - create_on
     * @var date
     */
    protected $_createOn;
    /**
     * table filed - create_by
     * @var int
     */
    protected $_createBy;
    /**
     * table filed - change_on
     * @var date
     */
    protected $_changeOn;
    /**
     * table filed - change_by
     * @var int
     */
    protected $_changeBy;

    /**
     * Use for assign values for private variables
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function addCheckingBooking() {
        $data = array(
            'patient_id' => $this->_patient_id,
            'checking_id' => $this->_checking_id,
            'comment' => $this->_comment,
            'date' => $this->_date,
            'create_by' => $this->_createBy,
            'create_on' => $this->_createOn,
            'change_by' => $this->_changeBy,
            'change_on' => $this->_changeOn
        );

        return $this->insert($data);
    }

    public function getPatietCheckingList() {
        $select = $this->select()
                ->from(array('a' => 'checking_patient_map'))
                ->join(
                        array('b' => 'checking'), 'b.id = a.checking_id', array(
                    'id AS checkingID',
                    'name',
                    'payment',
                    'description'
                        )
                )
                ->join(
                        array('c' => 'patient'), 'c.patient_id = a.patient_id ', array(
                    'id'
                        )
                )
               // ->where('c.status =?', true)
                ->where('c.type =?', 3)
                ->where('a.patient_id =?', $this->_patientId)
                ->order('b.name')
                ->setIntegrityCheck(false);

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

}

/*
 * 

    public function getPatietCheckingList() {
        $select = $this->select()
                ->from(array('a' => 'checking_patient_map'))
                ->join(
                    array('b' => 'checking'), 
                    'b.id = a.checking_id', 
                    array(
		            'id AS checkingID',
		            'name',
		            'payment',
		            'description'                    
                    )
                )
                ->where('status =?', true)
                ->where('a.patient_id =?', $this->_patientId)
                ->order('b.name')
                ->setIntegrityCheck(false);

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }
 * 
 * */
 