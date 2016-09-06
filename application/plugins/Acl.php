<?php

class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract 
{
    protected $_acl;

    public function __construct(Zend_Acl $acl) 
    {
        $this->_acl = $acl;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) 
    {        
        $cname = $request->getControllerName();
        $aname = $request->getActionName();
        
        $role = Zend_Registry::get('role');     
                        
        if ($this->_acl->isAllowed($role, $cname, $aname)) {

        } else {
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->gotoUrl('/');
        }
    }

}