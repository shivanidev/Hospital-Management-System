<?php

class SignupController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    
     /**
     * Use for add user details for database  
     * Get values from form
     * Add user details for user table
     * Upload profile image to public/images/users     
     */
    public function indexAction()
    {    	        
        $countryList = Application_Model_Utils::getCountryLIst();
        
        $options['countrylist'] = $countryList;
        
        $form = new Application_Form_SingupForm($options);
 
        $request = $this->getRequest();
        
       	if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                           
                $user = new Application_Model_Users();	
                
                $user->_id = null;
                $user->_email = $form->getValue('email');
                                
                if (
                    $user->checkUserName() 
                    && ($form->getValue('password') == $form->getValue('repassword'))
                ) {
                    $user->_password = md5($form->getValue('password'));
                    $user->_title = $form->getValue('title');
                    $user->_initials = $form->getValue('initials');
                    $user->_fname = $form->getValue('fname');
                    $user->_lname = $form->getValue('lname');  
                    $user->_bday = $form->getValue('bday');
                    $user->_telno = $form->getValue('telno');
                    $user->_address1 = $form->getValue('address1');
                    $user->_address2 = $form->getValue('address2');
                    $user->_city = $form->getValue('city');
                    
                    if ($form->getValue('country')) {                      
                        $user->_country = $countryList[$form->getValue('country')];
                    }
                                                                              
                    $user->_roleId = '7';

                    $today =  date('Y-m-d h:i:s');
                    $user->_createOn = $today;
                    $user->_changeOn = $today;

                    $submitUserId = '0';
                    $user->_createBy = $submitUserId;
                    $user->_changeBy = $submitUserId;

                    $userId = $user->addUser();
                    
                    if ($userId) {
                    	
                        $oldImageName = $form->getValue('image');

                        if (isset($oldImageName)) {
                            $temp = explode(".", $oldImageName);
                            $ext = $temp[count($temp)-1];

                            $userNameArray = explode("@", $form->getValue('email'));

                            $imageName = $user->_image = $userNameArray[0] .'_'. $userId .'.'. $ext;

                            $fullPath  = "images/users/". $imageName; 

                            copy("images/tmp/". $oldImageName, $fullPath);

                            $user->_id = $userId;
                            $user->updateImage();
                        }	                    
                    	
                        $session = new Zend_Session_Namespace('default');
                                       
                        if ($session->doctorChanneling || $session->checkingBook || $session->labtestBook) {
                            $this->_redirect('/payment');
                        } 
                            
                        $this->view->message = array('success', 'User Sing up sucessfully');
                    }
                                        
                } else {
                    
                    $errors = array();
                    
                    if (!$user->checkUserName()) {                        
                        $errors['usernameE'] = array(
                            'status' => 'errors', 
                            'text' => 'User name already exsist'
                        );
                    }
                    
                    if ($form->getValue('password') != $form->getValue('repassword')) {
                        $errors['passwordE'] = array(
                            'status' => 'errors', 
                            'text' => 'Password not match'
                        );
                    }
                  
                    $this->view->errors= $errors;	                    
                }                
            }
        }
 
        $this->view->form = $form;
    }
    
}
