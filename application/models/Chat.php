<?php

class Application_Model_Chat extends Zend_Db_Table_Abstract {

    /**
     * table name
     * @var string
     */
    protected $_name = 'chat';
    /**
     * table primary key (AI)
     * table filed - id 
     * @var int 
     */
    protected $_id;
       
    /**
     * table filed - chat_from
     * @var string
     */
    protected $_chatfrom;
    
    /**
     * table filed - chat_to
     * @var string
     */
    protected $_chatto;
    
    /**
     * table filed - message
     * @var string
     */
    protected $_message;
    
    /**
     * table filed - sent
     * @var date
     */
    protected $send;
    
    /**
     * table filed - recd
     * @var date
     */
    protected $_recd;

    /**
     * Use for assign values for private variables
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }



    public function addChatMessage() {
        $data = array(
            'chat_from' => $this->_chatfrom,
            'chat_to' => $this->_chatto,
            'message' => $this->_message,
            'sent' => $this->_sent
        );

        return $this->insert($data);
    }

    public function getChatData() {

        $select = $this->select()
                ->from(array('a' => 'chat'), array('chat_to AS to', 'chat_from AS from', 'message', 'sent'))
                ->where('a.chat_to = ?', $this->_chatto)
                ->where('recd = ?', 0)
                ->order('a.id')
                ->setIntegrityCheck(false);

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

    public function updateChat() {

        $data = array('recd' => 1);


        $where = array('chat_to = ?' => $this->_chatto, 'recd = ?' => 0);

        $this->update($data, $where);
    }

    
    /**
     * Use for get online users related to current log in user 
     */
    public function getUsers($id, $role) {

        $select;

        switch ($role) {

            case 'admin':

                //all log in users
                $select = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->where('status =?', 1)
                        ->where('a.id !=?', $id)
                        ->where('a.online_status =?', 1)
                        ->order('b.name')
                        ->setIntegrityCheck(false);

                break;

            case 'doctor':

                //all related log in patients
                $select1 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->join(array('c' => 'doctor_patient_map'), 'c.patient_id = a.id', array())
                        ->join(array('d' => 'doctor'), 'd.id = c.doctor_id', array())
                        ->where('d.user_id =?', $id)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);


                //all log in labtest  users
                $select2 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->where('a.role_id =?', 3)
                        ->where('status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                //all log in reservation users
                $select3 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->where('a.role_id =?', 5)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);


                //all log in checking users
                $select4 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->where('a.role_id =?', 4)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);


                $select = $this->select()
                        ->union(array($select1, $select2, $select3, $select4))
                        ->order("role");

                break;

            case 'patient':

                //all related log in doctors
                $select1 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->join(array('d' => 'doctor'), 'user_id = a.id', array())
                        ->join(array('c' => 'doctor_patient_map'), 'c.doctor_id = d.id', array())
                        ->where('c.patient_id =?', $id)
                        ->where('status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                //all log in labtest users if "patient is regstered for labtests" 
                $select2 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->join(array('d' => 'labtest_patient_map'), 'patient_id = ' . $id, array())
                        ->where('a.role_id =?', 3)
                        ->where('status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                //all log in reservation users "if patient is registered for reservations"
                $select3 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->join(array('d' => 'reservation'), 'patient_id  = ' . $id, array())
                        ->where('a.role_id =?', 5)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                // all log in checkings users "if pation is registred for checkings"
                $select4 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->join(array('d' => 'checking_patient_map'), 'patient_id  = ' . $id, array())
                        ->where('a.role_id =?', 4)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                $select = $this->select()
                        ->union(array($select1, $select2, $select3, $select4))
                        ->order("role");

                break;

            case 'lbuser':

                //get all log in patients registerd for lab tests
                $select1 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->join(array('c' => 'labtest_patient_map'), 'c.patient_id = a.id', array())
                        ->where('a.role_id =?', 7)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                //get all log in doctors
                $select2 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->where('a.role_id =?', 2)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                $select = $this->select()
                        ->union(array($select1, $select2))
                        ->order("role");
                break;

            case 'chuser':
                // all log in patients registerd for checkings
                $select1 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->join(array('c' => 'checking_patient_map'), 'c.patient_id = a.id', array())
                        ->where('a.role_id =?', 7)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                // all log in doctors
                $select2 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->where('a.role_id =?', 2)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                $select = $this->select()
                        ->union(array($select1, $select2))
                        ->order("role");
                break;

            case 'rsuser':
                // all log in patients registerd for reservations
                $select1 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->join(array('c' => 'reservation'), 'c.patient_id = a.id', array())
                        ->where('a.role_id =?', 7)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                // all log in doctors
                $select2 = $this->select()
                        ->from(array('a' => 'user'), array(
                            'id',
                            'title',
                            'initials',
                            'first_name',
                            'last_name')
                        )
                        ->join(array('b' => 'role'), 'a.role_id = b.id', array('id AS role_id', 'name AS role'))
                        ->where('a.role_id =?', 2)
                        ->where('a.status =?', 1)
                        ->where('a.online_status =?', 1)
                        ->setIntegrityCheck(false);

                        $select = $this->select()
                        ->union(array($select1, $select2))
                        ->order("role");
                break;
        }

        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            return false;
        }
    }

}