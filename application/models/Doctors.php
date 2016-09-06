<?php

class Application_Model_Doctors extends Zend_Db_Table_Abstract 
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
     * table filed - user_id
     * @var int
     */
    protected $_userId;
    /**
     * table filed - degree
     * @var string
     */
    protected $_degree;
    /**
     * table filed - payment
     * @var float
     */
    protected $_payment;
    /**
     * table filed - description
     * @var string
     */
    protected $_description;
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
    public function __set($name, $value) 
    {
        $this->$name = $value;
    }

    /**
     * Use for add details for docotr
     * @return int 
     */
    public function addDoctor() 
    {
        $data = array(
            'user_id' => $this->_userId,
            'degree' => $this->_degree,
            'payment' => $this->_payment,
            'description' => $this->_description,
            'create_on' => $this->_createOn,
            'create_by' => $this->_createBy,
            'change_on' => $this->_changeOn,
            'change_by' => $this->_changeBy
        );

        return $this->insert($data);
    }

    /**
     * Use for edit doctor details
     */
    public function editDoctor() 
    {
        $data = array(
            'degree' => $this->_degree,
            'payment' => $this->_payment,
            'description' => $this->_description,
            'change_on' => $this->_changeOn,
            'change_by' => $this->_changeBy
        );

        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);
    }

    /**
     * Use for get doctor details for show in doctor/index
     * @return array | false
     */
    public function getDoctors() 
    {
        $select = $this->select()
                ->from(array('a' => 'doctor'), array('id AS doctorID'))
                ->join(
                    array('b' => 'user'), 
                    'a.user_id = b.id', 
                    array(
                        'id AS userID',
                        'email',
                        'title',
                        'initials',
                        'first_name',
                        'last_name',                        
                        'tel_no'
                    )
                )
                ->join(array('c' => 'doctor_spe_map'), 'a.id = c.doctor_id', '')
                ->join(
                    array('d' => 'specialties'), 
                    'c.specialist_id = d.id', 
                    array('title AS special_title')
                )
                ->where('status =?', true)
                ->order('b.first_name')
                ->setIntegrityCheck(false);

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    /**
     * Use for get details for required doctor 
     */
    public function getDoctor() 
    {
        $select = $this->select()
                ->from(
                    array('a' => 'doctor'), 
                    array(
                        'id AS doctor_id',
                        'degree',
                        'payment',
                        'description'
                    )
                )
                ->join(
                    array('b' => 'user'), 
                    'a.user_id = b.id', 
                    array(
                        'id',
                        'email',
                        'title',
                        'initials',
                        'first_name AS fname',
                        'last_name AS lname',
                        'bday',
                        'tel_no AS telno',
                        'address1',
                        'address2',
                        'city',
                        'country',
                        'image'
                    )
                )
                ->where('status =?', true)
                ->where('a.user_id =?', $this->_userId)
                ->order('b.first_name')
                ->setIntegrityCheck(false);

        $result = $this->fetchRow($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    /**
     * Use for get doctor details for show in view doctor detials
     * @return array | false
     */
    public function getDoctorDetails()
    {
        $select = $this->select()
                        ->from(
                            array('a' => 'doctor'),
                            array(
                                'id',
                                'degree',
                                'payment',
                                'description'
                            )
                         )
                         ->where('user_id =?', $this->_userId);
        
        $reselt = $this->fetchRow($select);
        
        if ($reselt) {
            return $reselt->toArray();
        } else {
            return false;
        }
    }


    /**
     * Use for delete doctors
     * @return true | false
     */
    public function deleteDoctor() {
        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->delete($where);
    }

}