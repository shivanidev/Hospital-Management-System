<?php
class Application_Model_Reservation extends Zend_Db_Table_Abstract 
{
	protected $_name = 'reservation';
	protected $_id;
	protected $_patient_id;
	protected $_room_id;
	protected $_from_time;
	protected $_to_time;
	protected $_status;
	protected $_description;
	protected $_create_by;
	protected $_create_on;
	protected $_change_by;
	protected $_change_on;
	

    /**
     * Use for assign values for private variables
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value) 
    {
        $this->$name = $value;
    }
    
    
    public function getResrvations()
    {
    	$select = $this->select()
                       ->from(array('a' => 'reservation'),array('id','description'))
                       ->join(array('b' => 'room'), 'a.room_id = b.id', 'name AS roomname')
                       ->join(array('c' => 'user'), 'a.patient_id = c.id', array('first_name','last_name'))
    	               ->where('a.status =?', true)
    	               ->setIntegrityCheck(false);
    	               
        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }  
    }

    public function getResrvation()
    {
    	$select = $this->select()
                       ->from(array('a' => 'reservation'),array('id','description','from_time','to_time'))
                       ->join(array('b' => 'room'), 'a.room_id = b.id', array('room_id'=>'id','name'))
                       ->join(array('c' => 'user'), 'a.patient_id = c.id', array('patient_id'=>'id','first_name','last_name'))
    	               ->where('a.status =?', true)
    	               ->where('a.id =?', $this->_id)
    	               ->setIntegrityCheck(false);
    	               
        $result = $this->fetchRow($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }  
    }
    
    public function addReservation()
    {
    	$data = array(
            'patient_id'  => $this->_patient_id,
            'room_id'   => $this->_room_id,
            'from_time'   => $this->_from_time,
            'to_time' => $this->_to_time,
            'description' => $this->_description,
            'create_by' => $this->_create_by,
            'create_on' => $this->_create_on
        );

        return $this->insert($data);
    }
    
    public function updateReservation()
    {
    	$data = array(            
            'room_id'   => $this->_room_id,
            'from_time'   => $this->_from_time,
            'to_time' => $this->_to_time,
            'description' => $this->_description,
            'change_by' => $this->_change_by,
            'change_on' => $this->_change_on
        );

        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);   
    }
    
    public function deleteReservation()
    {
    	$data = array(
            'status' => 0,
    	    'change_by' => $this->_change_by,
            'change_on' => $this->_change_on
        );

        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);  
    }   	
}