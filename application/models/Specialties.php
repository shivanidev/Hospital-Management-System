<?php

class Application_Model_Specialties extends Zend_Db_Table_Abstract
{
    /**
     * table name
     * @var string
     */
    protected $_name = 'doctor';

    /**
     * table primary key (AI)
     * table filed - id 
     * @var int 
     */
    protected $_id;
    
    /**
     * table filed - title
     * @var string
     */
    protected $_title;

    /**
     * table filed - description
     * @var string
     */
    protected $_description;

    /**
     * Use for assign values for private variables
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    
    /**
     * Use for get specialities form specialties
     * @return array | false
     */
    public function getSpecialtiesList()
    {
    	$select = $this->select()
                        ->from(
                            array('a' => 'specialties'), 
                            array('key' => 'id', 'value' => 'title')
                        )
                        ->order('title')
                        ->setIntegrityCheck(false);
    					
    	$result = $this->fetchAll($select);
    	if ($result) {
            return $result->toArray();
    	} else {
            return false;
    	}
    }
    
    
    public function getDoctorSpecialtiesList($doctorId, $short = true)
    {
        if ($short) {
            $columList = array('id');
        } else {
            $columList = array('id', 'title', 'description');
        }
        
        $select = $this->select()
                        ->from(array('a' => 'specialties'), $columList)
                        ->join(
                            array('b' => 'doctor_spe_map'), 
                            'a.id = b.specialist_id', 
                            ''
                        )     
                        ->where('doctor_id =?', $doctorId)
                        ->setIntegrityCheck(false);
    					
    	$result = $this->fetchAll($select);
    	if ($result) {
            return $result->toArray();
    	} else {
            return false;
    	}
    }
    
}