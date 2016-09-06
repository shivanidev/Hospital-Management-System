<?php

class Application_Model_Room extends Zend_Db_Table_Abstract 
{

    protected $_name = 'room';
    protected $_id;
    protected $_roomname;
    protected $_quantity;
    protected $_payment;
    protected $_status;
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

    public function getRooms() 
    {
        $select = $this->select()
                ->where('status =?', true);               

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    public function addRoom()
    {
        $data = array(
            'name' => $this->_roomname,
            'quantity' => $this->_quantity,
            'payment' => $this->_payment,
            'create_on' => $this->_create_on,
            'create_by' => $this->_create_by
        );

        return $this->insert($data);
    }

    public function getRoom() 
    {
        $select = $this->select()
                ->where('id =?', $this->_id)
                ->setIntegrityCheck(false);

        $result = $this->fetchRow($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    public function updateRoom() 
    {
        $data = array(
            'name' => $this->_roomname,
            'quantity' => $this->_quantity,
            'payment' => $this->_payment,
            'change_on' => $this->_change_on,
            'change_by' => $this->_change_by
        );

        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);
    }

    public function deleteRoom() 
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