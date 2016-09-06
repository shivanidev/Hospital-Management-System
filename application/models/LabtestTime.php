<?php

class Application_Model_LabtestTime extends Zend_Db_Table_Abstract {

    /**
     * table name
     * @var string
     */
    protected $_name = 'labtest_time';
    /**
     * table primary key (AI)
     * table filed - id 
     * @var int 
     */
    protected $_id;
    
    protected $_labtest_id;
    protected $_day;
    protected $_from_time;
    protected $_to_time;

    /**
     * Use for assign values for private variables
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function addLabtestTime() {
        $data = array(
            'labtest_id' => $this->_labtest_id,
            'day' => $this->_day,
            'from_time' => $this->_from_time,
            'to_time' => $this->_to_time
        );

        return $this->insert($data);
    }
    
    
        /*
     * get labtest time details for labtest edit
     */

    public function getLabtestTime() {
        $select = $this->select()
                ->from(array('a' => 'labtest_time'), array('day', 'from_time', 'to_time'))
                ->where('a.labtest_id =?', $this->_labtest_id)
                ->Order('a.id')
                ->setIntegrityCheck(false);

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }


    /*
    public function updateLabtestTime() {
        $data = array(
            'day' => $this->_day,
            'from_time' => $this->_from_time,
            'to_time' => $this->_to_time
        );

        $where = $this->getAdapter()->quoteInto('cheking_id = ?', $this->_labtest_id);

        return $this->update($data, $where);
    }
     * 
     * */
     
    
    public function deleteLabtestTime()
    {
        $where = $this->getAdapter()->quoteInto('labtest_id = ?', $this->_labtest_id);
		
	return $this->delete($where);
    }

}

?>
