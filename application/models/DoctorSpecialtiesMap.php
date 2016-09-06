<?php
class Application_Model_DoctorSpecialtiesMap extends Zend_Db_Table_Abstract
{
    /**
     * table name
     * @var string
     */
    protected $_name = 'doctor_spe_map';
	
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
     * table filed - specialist_id
     * @var int
     */
    protected $_specialistId;
	
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
     * Use for add row for table
     * @return int | false
     */
    public function addDoctorSpetiolity()
    {
    	$data = array(
    		'doctor_id' => $this->_doctorId,
    		'specialist_id' => $this->_specialistId
    	);
    	
    	return $this->insert($data);
    }
    
    
    /**
     * Use for delete rows for required doctor id
     * @return true | false
     */
    public function deleteDoctorSpetiolities()
    {
    	 $where = $this->getAdapter()->quoteInto('doctor_id = ?', $this->_doctorId);
         return $this->delete($where);
    }
    
}