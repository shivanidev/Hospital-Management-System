<?php

class Application_Model_Users extends Zend_Db_Table_Abstract 
{
    /**
     * table name
     * @var string
     */
    protected $_name = 'user';
    /**
     * table primary key (AI)
     * table filed - id 
     * @var int 
     */
    protected $_id;
    /**
     * use for user name
     * table filed - email
     * @var string (email)
     */
    protected $_email;
    /**
     * table filed - password
     * @var sting (MD5)
     */
    protected $_password;
    /**
     * table filed - title
     * @var string (MR, MRS, MS)
     */
    protected $_title;
    /**
     * table filed - first_name
     * @var string
     */
    protected $_fname;
    /**
     * table filed - last_name
     * @var string 
     */
    protected $_lname;
    /**
     * table filed - initials
     * @var string
     */
    protected $_initials;
    /**
     * table filed - bday
     * @var date
     */
    protected $_bday;
    /**
     * table filed - telno
     * @var int (10)
     */
    protected $_telno;
    /**
     * table filed - address1
     * @var string
     */
    protected $_address1;
    /**
     * table filed - address2
     * @var string
     */
    protected $_address2;
    /**
     * table filed - countory
     * @var string
     */
    protected $_city;
    /**
     * table filed - country
     * @var string
     */
    protected $_country;
    /**
     * table filed - image
     * @var string
     */
    protected $_image;
    /**
     * table filed - role_id
     * @var int
     */
    protected $_roleId;
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
     * Use for add user
     * @return int (row id) | false
     */
    public function addUser() 
    {
        $data = array(
            'email' => $this->_email,
            'password' => $this->_password,
            'title' => $this->_title,
            'first_name' => $this->_fname,
            'last_name' => $this->_lname,
            'initials' => $this->_initials,
            'bday' => $this->_bday,
            'tel_no' => $this->_telno,
            'address1' => $this->_address1,
            'address2' => $this->_address2,
            'city' => $this->_city,
            'country' => $this->_country,
            'role_id' => $this->_roleId,
            'create_on' => $this->_createOn,
            'create_by' => $this->_createBy,
            'change_on' => $this->_changeOn,
            'change_by' => $this->_changeBy
        );

        return $this->insert($data);
    }

    /**
     * Use for edit user details
     * @return int (num of row) | false
     */
    public function editUser()
    {
        $data = array(
            'title' => $this->_title,
            'first_name' => $this->_fname,
            'last_name' => $this->_lname,
            'initials' => $this->_initials,
            'bday' => $this->_bday,
            'tel_no' => $this->_telno,
            'address1' => $this->_address1,
            'address2' => $this->_address2,
            'city' => $this->_city,
            'country' => $this->_country,
            'role_id' => $this->_roleId,
            'change_on' => $this->_changeOn,
            'change_by' => $this->_changeBy
        );

        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);
    }

    /**
     * Use for update image field after upload profile image
     */
    public function updateImage() 
    {
        $data = array('image' => $this->_image);

        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);
    }

    /**
     * Use for update user login details
     * @return int | false
     */
    public function updateLoginDetails() 
    {
        $data = array(
            'email' => $this->_email,
            'password' => $this->_password
        );

        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);
    }

    /**
     * Use for delete user
     * Change status as false
     * @return true | false
     */
    public function deleteUser() 
    {
        $data = array(
            'status' => 0,
            'change_on' => $this->_changeOn,
            'change_by' => $this->_changeBy
        );

        $where = $this->getAdapter()->quoteInto('id = ?', $this->_id);

        return $this->update($data, $where);
    }

    /**
     * Use to keep email is uniq
     * @return true | false
     */
    public function checkUserName() 
    {
        if (isset($this->_id)) {
            $validator = new Zend_Validate_Db_NoRecordExists(
                            "user",
                            "email",
                            "status = 1 AND id !=" . $this->_id
            );
        } else {
            $validator = new Zend_Validate_Db_NoRecordExists(
                            "user",
                            "email",
                            "status = 1"
            );
        }

        return $validator->isValid($this->_email);
    }

    /**
     * Use for get all non deleted users for make users list in user index
     * @return array | false
     */
    public function getUsers() 
    {
        $select = $this->select()
                ->from(
                        array('a' => 'user'), array(
                    'id',
                    'email',
                    'title',
                    'initials',
                    'first_name',
                    'last_name',
                    'tel_no'
                        )
                )
                ->join(
                        array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role')
                )
                ->where('status =?', true)
                ->setIntegrityCheck(false);

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    /**
     * Use for get user login details
     */
    public function getUserLoginDetails() 
    {
        $select = $this->select()
                ->from(array('a' => 'user'), array('id', 'email'))
                ->where('id =?', $this->_id);

        $result = $this->fetchRow($select);

        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    /**
     * Use for get user details to show user details in view user
     * @return array | false
     */
    public function getUser() 
    {
        $select = $this->select()
                ->from(
                    array('a' => 'user'), 
                    array(
                        'id',
                        'email',
                        'title',
                        'initials',
                        'first_name',
                        'last_name',
                        'bday',
                        'tel_no',
                        'address1',
                        'address2',
                        'city',
                        'country',
                        'image',
                        'role_id'
                    )
                )
                ->join(array('b' => 'role'), 'a.role_id = b.id', array('name AS role'))
                ->where('a.id =?', $this->_id)
                ->setIntegrityCheck(false);

        $result = $this->fetchRow($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    public function setLoginStatus($username, $status) 
    {
        if ($status == 'login') {
            $statusVal = 1;
        } else {
            $statusVal = 0;
        }

        $data = array('online_status' => $statusVal);

        $where = $this->getAdapter()->quoteInto('email = ?', $username);

        return $this->update($data, $where);
    }

    public function getAllPatients() 
    {
        $select = $this->select()
                ->where('role_id =?', 7)
                ->setIntegrityCheck(false);

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    public function getPatients($state, $id = null) 
    {
         $select = $this->select()
                        ->from(
                            array('a' => 'user'),
                            array(
                                'id AS patientID',
                                'title',
                                'first_name',
                                'last_name',
                                'initials',
                            )
                        )
                        ->join(
                            array('b' => 'patient'), 
                            'a.id =b.patient_id', 
                            array('SUM(b.status) AS checkedSum')
                        );                       
        
        switch ($state) {

            case 'admin':               
                break;

            case 'doctor':               
                $select->join(array('c' => 'doctor_patient_map'), 'a.id = c.patient_id ', '')
                       ->join(array('d' => 'doctor'), 'd.id = c.doctor_id', '')                       
                       ->where('b.type =?', 1)
                       ->where('d.user_id =?', $id);                        
                break;

            case 'chuser':
                $select->where('b.type =?', 3);                        
                break;

            case 'lbuser':
                $select->where('b.type =?', 2);                       
                break;
        }
        
        $select->where('a.status =?', true)
                ->group('b.patient_id')
                ->distinct(TRUE)
                ->setIntegrityCheck(false);
        
        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

}