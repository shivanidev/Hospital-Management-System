<?php

class Application_Model_DoctorTime extends Zend_Db_Table_Abstract {

    /**
     * table name
     * @var string
     */
    protected $_name = 'doctor_time';
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
     * table filed - day
     * @var enum('sunday','monday','tuesday','wednesday','thuesday','friday','saturday')
     */
    protected $_day;
    /**
     * table filed - from_time
     * @var time (00:00:00 - 23:59:59)
     */
    protected $_fromTime;
    /**
     * table filed - to_time
     * @var time (00:00:00 - 23:59:59)
     */
    protected $_toTime;

    /**
     * Use for assign values for private variables
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    /**
     * Use for add row for table
     * @return int | false
     */
    public function addDoctorTime() {
        $data = array(
            'doctor_id' => $this->_doctorId,
            'day' => $this->_day,
            'from_time' => $this->_fromTime,
            'to_time' => $this->_toTime
        );

        return $this->insert($data);
    }

    public function getDoctorTimeDetails($doctorId) {
        $select = $this->select()
                ->from(
                        array('a' => 'doctor_time'), array('day', 'from_time', 'to_time')
                )
                ->where('doctor_id =?', $doctorId)
                ->Order('a.id');

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    /**
     * Use for delete rows for required doctor id
     * @return true | false
     */
    public function deleteDoctorTimes() {
        $where = $this->getAdapter()->quoteInto('doctor_id = ?', $this->_doctorId);
        return $this->delete($where);
    }

}