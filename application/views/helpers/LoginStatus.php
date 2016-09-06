<?php

class Zend_View_Helper_LoginStatus extends Zend_View_Helper_Abstract
{
    public function loginStatus($loginData)
    {         
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
           
    	if ($loginData != null) {
            
            $userId = Zend_Auth::getInstance()->getStorage()->read()->id;
            
            if (Zend_Registry::get('role') == 'doctor') {
                $controller = 'doctors'; 
            } else {
                $controller = 'users';
            }
            
            $loginStatus = '<span>'
                        .   'Welcome, ' . $loginData->first_name . ' ' . $loginData->last_name . '<br/>'
                        .   '<a href="'. $baseUrl .'/'. $controller .'/view/id/'. $userId .'">View Profie</a> | '
                        .   '<a href="'. $baseUrl . '/signout">Sign out</a>'                       
                        .   '</span>';
        } else {
            $loginStatus = '<span>You are not logged in. '
                        .   '<a href="'. $baseUrl .'/signin/">Sign in</a> | ' 
                        .   '<a href="'. $baseUrl .'/signup/">Sign up</a></span>';	
        }

        return $loginStatus;
    }
}
