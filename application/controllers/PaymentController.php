<?php

class PaymentController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        
        $form = new Application_Form_PaymentForm();
        
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $session = new Zend_Session_Namespace('default');
                
                if (isset($session->doctorChanneling)) {
                    $this->_redirect('/doctors/confirm');
                }   
                
                if (isset($session->checkingBook)) {
                    $this->_redirect('/checking/confirm');
                }  
                
                if (isset($session->labtestBook)) {
                    $this->_redirect('/labtest/confirm');
                }  
            }
        }
        
        $this->view->form = $form;
    }


}

