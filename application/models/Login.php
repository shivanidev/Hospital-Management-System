<?php

class Application_Model_Login extends Zend_Auth {

    protected $_authAdapter;

    public function __construct() {
        $this->_authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $this->_authAdapter->setTableName('user')//db table name
                ->setIdentityColumn('email')//username column
                ->setCredentialColumn('password')//password column
                ->setCredentialTreatment('MD5(?)'); //md5
    }

    public function setData($username, $password) {    	
        $this->_authAdapter->setIdentity($username)
                ->setCredential($password);
    }

    public function isValid() {    	
        if ($this->_authAdapter->authenticate()->isValid()) {
            $identify = $this->_authAdapter->getResultRowObject();
            $storage = $this->getStorage();
            $storage->write($identify);
            print_r($storage);
            return true;
        } else {
            return false;
        }
    }

}

