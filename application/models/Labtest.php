<?php

class Application_Model_Labtest extends Zend_Db_Table_Abstract {

    /**
     * table name
     * @var string
     */
    protected $_name = 'labtest';
    /**
     * table primary key (AI)
     * table filed - id 
     * @var int 
     */
    protected $_id;
    /**
     * table filed - name
     * @var int
     */
    protected $_lname;
    /**
     * table filed - payment
     * @var int
     */
    protected $_payment;
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
    protected $_status;

    /**
     * Use for assign values for private variables
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    /*
     * use for add labtest detail 
     */

    public function addLabtest() {
        $data = array(
            'name' => $this->_lname,
            'payment' => $this->_payment,
            'description' => $this->_description,
            'create_on' => $this->_create_on,
            'create_by' => $this->_create_by
        );

        return $this->insert($data);
    }

    /*
     * get details for labtest list view
     */

    public function getLabtests() {

        $select = $this->select()
                ->from(array('a' => 'labtest'), array('name', 'id'))
                ->where('status = ?', 1)
                ->order('a.id')
                ->setIntegrityCheck(false);


        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    /*
     * get labtest details for labtest edit
     */

    public function getLabtest() {
        $select = $this->select()
                ->from(array('a' => 'labtest'), array('id', 'name', 'payment', 'description', 'create_by'))
                ->where('a.id =?', $this->_id)
                ->setIntegrityCheck(false);


        $result = $this->fetchRow($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    /*
     * use for  labtest detail update
     */

    public function updateLabtest() {
        $data = array(
            'name' => $this->_cname,
            'payment' => $this->_payment,
            'description' => $this->_description,
            'change_on' => $this->_change_on,
            'change_by' => $this->_change_by
        );

        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);
    }

    /*
     * use for delete a labtest record
     */

    public function deleteLabtest() {
        $data = array(
            'status' => $this->_status
        );
        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);
    }

    public function checkName() {

        $validator = new Zend_Validate_Db_NoRecordExists(
                        "labtest",
                        "name",
                        "status = 1"
        );

        return $validator->isValid($this->_lname);
    }

}

