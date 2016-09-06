<?php

class Application_Model_AdminUser extends Zend_Db_Table_Abstract
{
    /**
     * table name
     * @var string
     */
    protected $_name = 'admin_user';
   
    /**
     * table primary key (AI)
     * table filed - id 
     * @var int 
     */
    protected $_id;
   
    /**
     * table filed - user_id
     * @var int
     */
    protected $_userId;  
    
    /**
     * table filed - description
     * @var string
     */
    protected $_description;
    
    /**
     * table filed - create_by
     * @var string
     */
    protected $_create_by;
   
    /**
     * table filed - create_on
     * @var string
     */
    protected $_create_on;
    
    /**
     * table filed - change_by
     * @var string
     */
    protected $_change_by;
   
    /**
     * table filed - change_on
     * @var string
     */
    protected $_change_on;
    /**
     * table filed - status
     * @var int
     */

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
     * Use for get details for view
     * @return array | false
     */
    public function getDetails()
    {
        $select = $this->select()
                        ->from(array('a' => 'admin_user'), array('description'))
                        ->where('user_id =?', $this->_userId);
        
        $result = $this->fetchRow($select);
        
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }
    
}