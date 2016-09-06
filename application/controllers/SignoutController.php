<?php

class SignoutController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {

        $username = Zend_Auth::getInstance()->getStorage()->read()->email;
        $users = new Application_Model_Users();
        $users->setLoginStatus($username, 'logout');
        
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect('/');
    }

}

