<?php

class Application_Model_Acl extends Zend_Acl 
{

    public function __construct() 
    {
        $roles = array('admin', 'doctor', 'lbuser', 'chuser', 'rsuser', 
            'nmuser', 'patient', 'guest');
        
        $resources = array('about', 'chat', 'checking', 'doctors', 'error', 'index', 
            'labtest', 'patients', 'payment', 'reservations', 'signin', 'room',
            'signout', 'signup', 'users');
        
        foreach ($roles as $role) {           
             $this->addRole(new Zend_Acl_Role($role));
        }
        
        foreach ($resources as $resource) {
             $this->addResource(new Zend_Acl_Resource($resource));
        }

        /** Administrator */
        $this->allow('admin', 'about'); 
        $this->allow('admin', 'chat');
        $this->allow('admin', 'checking');
        $this->allow('admin', 'doctors');       
        $this->allow('admin', 'error');
        $this->allow('admin', 'index');                
        $this->allow('admin', 'labtest');
        $this->allow('admin', 'patients');
        $this->allow('admin', 'payment');
        $this->allow('admin', 'reservations');        
        $this->allow('admin', 'room');               
        $this->allow('admin', 'signout');
        $this->allow('admin', 'users');
       
        $this->deny('admin', 'signin');
        $this->deny('admin', 'signup');
              
        
        /** Doctor */
        $this->allow('doctor', 'about'); 
        $this->allow('doctor', 'chat');
        $this->allow('doctor', 'checking');
        $this->allow('doctor', 'doctors');       
        $this->allow('doctor', 'error');
        $this->allow('doctor', 'index');                
        $this->allow('doctor', 'labtest');
        $this->allow('doctor', 'patients');
        $this->allow('doctor', 'payment');                     
        $this->allow('doctor', 'signout'); 
        $this->allow('doctor', 'users', 'view');
        $this->allow('doctor', 'users', 'edit');
        $this->allow('doctor', 'users', 'changepassword');
       
        $this->deny('doctor', 'checking', 'add');
        $this->deny('doctor', 'checking', 'edit');
        $this->deny('doctor', 'checking', 'delete');
        $this->deny('doctor', 'doctors', 'add'); 
        $this->deny('doctor', 'doctors', 'delete'); 
        $this->deny('doctor', 'labtest', 'add'); 
        $this->deny('doctor', 'labtest', 'edit'); 
        $this->deny('doctor', 'labtest', 'delete');
        $this->deny('doctor', 'patients', 'delete');
        $this->deny('doctor', 'reservations'); 
        $this->deny('doctor', 'room'); 
        $this->deny('doctor', 'signin');
        $this->deny('doctor', 'signup');  
        
        /** Lab Test User */
        $this->allow('lbuser', 'about'); 
        $this->allow('lbuser', 'chat');
        $this->allow('lbuser', 'checking');
        $this->allow('lbuser', 'doctors');       
        $this->allow('lbuser', 'error');
        $this->allow('lbuser', 'index');                
        $this->allow('lbuser', 'labtest');
        $this->allow('lbuser', 'patients');
        $this->allow('lbuser', 'payment');                     
        $this->allow('lbuser', 'signout'); 
        $this->allow('lbuser', 'users', 'view');
        $this->allow('lbuser', 'users', 'edit');
        $this->allow('lbuser', 'users', 'changepassword');
       
        $this->deny('lbuser', 'checking', 'add');
        $this->deny('lbuser', 'checking', 'edit');
        $this->deny('lbuser', 'checking', 'delete');
        $this->deny('lbuser', 'doctors', 'changepassword'); 
        $this->deny('lbuser', 'doctors', 'add'); 
        $this->deny('lbuser', 'doctors', 'edit'); 
        $this->deny('lbuser', 'doctors', 'delete'); 
        $this->deny('lbuser', 'labtest', 'add'); 
        $this->deny('lbuser', 'labtest', 'edit'); 
        $this->deny('lbuser', 'labtest', 'delete');
        $this->deny('lbuser', 'patients', 'delete');
        $this->deny('lbuser', 'reservations'); 
        $this->deny('lbuser', 'room'); 
        $this->deny('lbuser', 'signin');
        $this->deny('lbuser', 'signup');  
        
        
        /** Checking User */
        $this->allow('chuser', 'about'); 
        $this->allow('chuser', 'chat');
        $this->allow('chuser', 'checking');
        $this->allow('chuser', 'doctors');       
        $this->allow('chuser', 'error');
        $this->allow('chuser', 'index');                
        $this->allow('chuser', 'labtest');
        $this->allow('chuser', 'patients');
        $this->allow('chuser', 'payment');                     
        $this->allow('chuser', 'signout'); 
        $this->allow('chuser', 'users', 'view');
        $this->allow('chuser', 'users', 'edit');
        $this->allow('chuser', 'users', 'changepassword');
       
        $this->deny('chuser', 'checking', 'add');
        $this->deny('chuser', 'checking', 'edit');
        $this->deny('chuser', 'checking', 'delete');
        $this->deny('chuser', 'doctors', 'changepassword'); 
        $this->deny('chuser', 'doctors', 'add');
        $this->deny('chuser', 'doctors', 'edit'); 
        $this->deny('chuser', 'doctors', 'delete'); 
        $this->deny('chuser', 'labtest', 'add'); 
        $this->deny('chuser', 'labtest', 'edit'); 
        $this->deny('chuser', 'labtest', 'delete');
        $this->deny('chuser', 'patients', 'delete');
        $this->deny('chuser', 'reservations'); 
        $this->deny('chuser', 'room'); 
        $this->deny('chuser', 'signin');
        $this->deny('chuser', 'signup');  
       
        
        /** Reservation User */
        $this->allow('rsuser', 'about'); 
        $this->allow('rsuser', 'chat');
        $this->allow('rsuser', 'checking');
        $this->allow('rsuser', 'doctors');       
        $this->allow('rsuser', 'error');
        $this->allow('rsuser', 'index');                
        $this->allow('rsuser', 'labtest');        
        $this->allow('rsuser', 'payment'); 
        $this->allow('rsuser', 'reservations'); 
        $this->allow('rsuser', 'room'); 
        $this->allow('rsuser', 'signout'); 
        $this->allow('rsuser', 'users', 'view');
        $this->allow('rsuser', 'users', 'edit');
        $this->allow('rsuser', 'users', 'changepassword');
       
        $this->deny('rsuser', 'checking', 'add');
        $this->deny('rsuser', 'checking', 'edit');
        $this->deny('rsuser', 'checking', 'delete');
        $this->deny('rsuser', 'doctors', 'changepassword'); 
        $this->deny('rsuser', 'doctors', 'add'); 
        $this->deny('rsuser', 'doctors', 'edit'); 
        $this->deny('rsuser', 'doctors', 'delete'); 
        $this->deny('rsuser', 'labtest', 'add'); 
        $this->deny('rsuser', 'labtest', 'edit'); 
        $this->deny('rsuser', 'labtest', 'delete');        
        $this->deny('rsuser', 'patients');       
        $this->deny('rsuser', 'signin');
        $this->deny('rsuser', 'signup');  
        
        
         /** Pateint */
        $this->allow('patient', 'about'); 
        $this->allow('patient', 'chat');
        $this->allow('patient', 'checking');
        $this->allow('patient', 'doctors');       
        $this->allow('patient', 'error');
        $this->allow('patient', 'index');                
        $this->allow('patient', 'labtest');       
        $this->allow('patient', 'payment');                     
        $this->allow('patient', 'signout'); 
        $this->allow('patient', 'users', 'view');
        $this->allow('patient', 'users', 'edit');
        $this->allow('patient', 'users', 'changepassword');
       
        $this->deny('patient', 'checking', 'add');
        $this->deny('patient', 'checking', 'edit');
        $this->deny('patient', 'checking', 'delete');
        $this->deny('patient', 'doctors', 'changepassword'); 
        $this->deny('patient', 'doctors', 'add'); 
        $this->deny('patient', 'doctors', 'edit'); 
        $this->deny('patient', 'doctors', 'delete'); 
        $this->deny('patient', 'labtest', 'add'); 
        $this->deny('patient', 'labtest', 'edit'); 
        $this->deny('patient', 'labtest', 'delete');
        $this->deny('patient', 'patients');
        $this->deny('patient', 'reservations'); 
        $this->deny('patient', 'room'); 
        $this->deny('patient', 'signin');
        $this->deny('patient', 'signup'); 
        
         /** Guest */
        $this->allow('guest', 'about');         
        $this->allow('guest', 'checking');
        $this->allow('guest', 'doctors');       
        $this->allow('guest', 'error');
        $this->allow('guest', 'index');                
        $this->allow('guest', 'labtest');                          
        $this->allow('guest', 'signin');
        $this->allow('guest', 'signup'); 
              
        $this->deny('guest', 'chat');
        $this->deny('guest', 'checking', 'add');
        $this->deny('guest', 'checking', 'edit');
        $this->deny('guest', 'checking', 'delete');
        $this->deny('guest', 'doctors', 'changepassword'); 
        $this->deny('guest', 'doctors', 'add'); 
        $this->deny('guest', 'doctors', 'edit'); 
        $this->deny('guest', 'doctors', 'delete'); 
        $this->deny('guest', 'labtest', 'add'); 
        $this->deny('guest', 'labtest', 'edit'); 
        $this->deny('guest', 'labtest', 'delete');
        $this->deny('guest', 'payment');  
        $this->deny('guest', 'patients');
        $this->deny('guest', 'reservations'); 
        $this->deny('guest', 'room'); 
        $this->deny('guest', 'signout');       
       
    }
    
}