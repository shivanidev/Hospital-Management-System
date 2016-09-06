<?php

class Application_Model_LabtestPatientMap extends Zend_Db_Table_Abstract {

    protected $_name = "labtest_patient_map";
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
    protected $_lab_test_id;
    /**
     * table filed - patient_id
     * @var int
     */
    protected $_patientId;
    /**
     * table filed - channel_date
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

    /**
     * Use for add row for the table
     * @return int | false
     */
    public function addLabtestBooking() {
        $data = array(
            'lab_test_id' => $this->_lab_test_id,
            'patient_id' => $this->_patientId,
            'date' => $this->_date,
            'comment' => $this->_comment,
            'create_on' => $this->_createOn,
            'create_by' => $this->_createBy,
            'change_on' => $this->_changeOn,
            'change_by' => $this->_changeBy
        );

        return $this->insert($data);
    }

//    public function getChanelDoctorsList()
//    {
//           $select = $this->select()
//            ->from(array('a' => 'doc_patient_map'), array('id', 'doctor_id', 'patient_id', 'channel_date', 'comment', 'create_by','create_on','change_by','change_on'))
//            ->join(
//                    array('b' => 'user'), 'b.id = a.doctor_id', array(
//                        'id AS userID',
//                        'email',
//                        'title',
//                        'initials',
//                        'first_name AS fname',
//                        'last_name',
//                        'bday',
//                        'tel_no'
//                    )
//            )
//            ->where('status =?', true)
//            ->order('b.first_name')
//            ->setIntegrityCheck(false);
//
//        $result = $this->fetchAll($select);
//        if ($result) {
//            return $result->toArray();
//        } else {
//            return false;
//        }
//    }

    public function getLabTestList() {
        $select = $this->select()
                ->from(array('a' => 'labtest_patient_map'), array('id', 'lab_test_id', 'patient_id', 'date', 'comment'))
                ->join(
                        array('b' => 'labtest'), 'b.id = a.lab_test_id', array(
                    'id AS labTestId',
                    'name',
                    'payment',
                    'description'               
                ))
                ->join(
                        array('c' => 'patient'), 'c.patient_id = a.patient_id', array('id')
                )
                ->where('a.patient_id =?', $this->_patientId)
                ->where('c.type =?', 2)
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