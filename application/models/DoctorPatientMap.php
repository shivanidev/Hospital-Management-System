<?php

class Application_Model_DoctorPatientMap extends Zend_Db_Table_Abstract {

    /**
     * table name
     * @var string
     */
    protected $_name = 'doctor_patient_map';
    /**
     * table primary key (AI)
     * table filed - id 
     * @var int 
     */
    protected $_id;
    /**
     * table filed - doctor_id
     * @var int
     */
    protected $_doctorId;
    /**
     * table filed - patient_id
     * @var int
     */
    protected $_patientId;
    /**
     * table filed - channel_date
     * @var date
     */
    protected $_channelDate;
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
     * table filed - change_by
     * @var int
     */
    protected $_checking_patient_status;

    /**
     * Use for assign values for private variables
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    /**
     * Use for add row for the table
     * @return int | false
     */
    public function addDoctorPatient() {
        $data = array(
            'doctor_id' => $this->_doctorId,
            'patient_id' => $this->_patientId,
            'channel_date' => $this->_channelDate,
            'comment' => $this->_comment,
            'create_on' => $this->_createOn,
            'create_by' => $this->_createBy,
            'change_on' => $this->_changeOn,
            'change_by' => $this->_changeBy
        );

        return $this->insert($data);
    }

    public function getChanelDoctorsList() {
        $select = $this->select()
                ->from(array('a' => 'doctor_patient_map'), array('channel_date', 'comment'))
                ->join(array('b' => 'patient'), 'b.patient_id = a.patient_id', array('id'))
                ->join(array('c' => 'doctor'), 'c.id = a.doctor_id','')
                ->join(
                    array('d' => 'user'), 
                    'd.id = c.user_id', 
                    array('id AS userId', 'title', 'first_name', 'last_name', 'initials')    
                )
                ->where('d.status =?', true)
                ->where('a.patient_id =?', $this->_patientId)
                ->where('b.type =?', 1)
                ->order('a.channel_date')
                ->setIntegrityCheck(false);

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    public function setCheckingPatientStatus() {

        $data = array(
            'checking_patient_status' => $this->_checking_patient_status);

        $where = $this->getAdapter()->quoteInto('patient_id = ?', $this->_patient_id);

        return $this->update($data, $where);
    }

//    public function getCheckingPatientStatus(){
//                $select = $this->select()
//                ->from(array('a' => 'doctor_patient_map'), array(
//                    'checking_patient_status')
//                )
//                ->where('patient_id =?', $this->_patientId);
//
//        $result = $this->fetchRow($select);
//        if ($result) {
//            return $result->toArray();
//        } else {
//            return false;
//        }
//    }
}