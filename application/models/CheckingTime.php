<?php

class Application_Model_CheckingTime extends Zend_Db_Table_Abstract {

    /**
     * table name
     * @var string
     */
    protected $_name = 'checking_time';
    /**
     * table primary key (AI)
     * table filed - id 
     * @var int 
     */
    protected $_id;
    
    protected $_checking_id;
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

    public function addCheckingTime() {
        $data = array(
            'checking_id' => $this->_checking_id,
            'day' => $this->_day,
            'from_time' => $this->_from_time,
            'to_time' => $this->_to_time
        );

        return $this->insert($data);
    }
    
    
        /*
     * get checking time details for checking edit
     */

    public function getCheckingTime() {
        $select = $this->select()
                ->from(array('a' => 'checking_time'), array('day', 'from_time', 'to_time'))
                ->where('a.checking_id =?', $this->_checking_id)
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
    public function updateCheckingTime() {
        $data = array(
            'day' => $this->_day,
            'from_time' => $this->_from_time,
            'to_time' => $this->_to_time
        );

        $where = $this->getAdapter()->quoteInto('cheking_id = ?', $this->_checking_id);

        return $this->update($data, $where);
    }
     * 
     * */
     
    
    public function deleteCheckingTime()
    {
        $where = $this->getAdapter()->quoteInto('checking_id = ?', $this->_checking_id);
		
	return $this->delete($where);
    }

}

?>
