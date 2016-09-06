<?php

class Zend_View_Helper_IsAllowed {

    function isAllowed($controller, $action)
    {
        $role = Zend_Registry::get('role');     
        
        $acl= new Application_Model_Acl();
        
        if ($acl->isAllowed($role, $controller, $action)) {
            return true;
        } else {
            return false;
        }
    }
	
}

