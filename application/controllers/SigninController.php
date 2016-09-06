<?php

class SigninController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    
    public function indexAction()
    {
       $form = new Application_Form_LoginForm();
       $request = $this->getRequest();
       if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
            	$username = $form->getValue('email');
                $password = $form->getValue('password');
             	
                $db = Zend_Registry::get("db");
    	
                $authAdapter = new Zend_Auth_Adapter_DbTable(
                    $db,
                    'user',
                    'email',
                    'password'    		
                );

                // get select object (by reference)
                $select = $authAdapter->getDbSelect();
                $select->where('status =?', true);

                // Set the input credential values to authenticate against
                $authAdapter->setIdentity($username);
                $authAdapter->setCredential($password);
                $authAdapter->setCredentialTreatment('MD5(?)');
                
                $auth = Zend_Auth::getInstance();
                $login = $auth->authenticate($authAdapter);
                
                if ($login->isValid()) {  
                    $identify = $authAdapter->getResultRowObject();
                    $storage = $auth->getStorage();
                    $storage->write($identify);
                    
                    $session = new Zend_Session_Namespace('default');
                                       
                    if ($session->doctorChanneling || $session->checkingBook || $session->labtestBook) {
                        $this->_redirect('/payment');
                    } 
                    
                    $users=new Application_Model_Users();
                    $users->setLoginStatus($username,'login');
                    
                    $this->_redirect('/');
                } else {
                    $this->view->message = array(
                        'errors', 
                        'User name or password incorrect'
                    );               	        	
                }
            }
       }
                   
       $this->view->form = $form;
    }


}

