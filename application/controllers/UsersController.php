<?php

class UsersController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $users = new Application_Model_Users();
        $usersDetails = $users->getUsers();
               
        $usersArray = array();

        foreach ($usersDetails as $user) {

            $name = $user['title'] . '. ' . $user['initials'] . ' '
                    . $user['first_name'] . ' ' . $user['last_name'];

            $role = $this->_getUserType($user['role']);

            $usersArray[$user['id']] = array(
                'name' => $name,
                'role_id' => $user['role_id'],
                'role' => $role
            );
        }

        $this->view->users = $usersArray;
    }

    public function viewAction() {

        $request = $this->getRequest();

        //get parameter from url	
        if ($request->getParam('id')) {
            $userId = $request->getParam('id');            
        } else {
            $this->_redirect('/users');
        }

        $users = new Application_Model_Users();
        $users->_id = $userId;
        $userDetails = $users->getUser();
            
        $userArray = array();
        $userArray['name'] = $userDetails['title'] . ' '
                . $userDetails['initials'] . ' '
                . $userDetails['first_name'] . ' '
                . $userDetails['last_name'];

        $userArray['email'] = $userDetails['email'];
        $userArray['bday'] = $userDetails['bday'];
        $userArray['telno'] = $userDetails['tel_no'];
        $userArray['image'] = $userDetails['image'];

        $address = '';

        if (isset($userDetails['address1']) && !empty($userDetails['address1'])) {
            $address .= $userDetails['address1'];
        }

        if (isset($userDetails['address2']) && !empty($userDetails['address2'])) {
            $address .= ', ' . $userDetails['address2'];
        }

        if (isset($userDetails['city']) && !empty($userDetails['city'])) {
            $address .= ', ' . $userDetails['city'];
        }

        if (isset($userDetails['country']) && !empty($userDetails['country'])) {
            $address .= ', ' . $userDetails['country'];
        }

        $userArray['address'] = $address;
        
        $userArray['user_type'] = $this->_getUserType($userDetails['role']);

        /*$adminArray = array();
        $doctorArray = array();
        $pateintArray = array();
        $lbuserArray = array();
        $chekinguserArray = array();

        if ($role == 1) { //admin  
            $adminUser = new Application_Model_AdminUser();
            $adminUser->_userId = $userId;
            $adminDetails = $adminUser->getDetails();
            $this->view->admin = $adminDetails;
        } else if ($role == 2) { //doctor
            $doctors = new Application_Model_Doctors();
            $doctors->_userId = $userId;
            $doctorDetails = $doctors->getDoctorDetails();

            $doctorId = $doctorDetails['id'];

            $specialties = new Application_Model_Specialties();
            $doctorSpecialities = $specialties->getDoctorSpecialtiesList($doctorId, false);

            $specialtiesArray = array();

            foreach ($doctorSpecialities as $speciality) {
                array_push($specialtiesArray, $speciality['title']);
            }

            $doctorDetails['specialties'] = implode(", ", $specialtiesArray);

            $doctorTime = new Application_Model_DoctorTime();
            $doctorTimeDetails = $doctorTime->getDoctorTimeDetails($doctorId);

            $doctorDetails['doctor_time'] = $doctorTimeDetails;

            if (!isset($userDetails['image'])) {
                $userArray['image'] = 'doctor';
            }

            $this->view->doctor = $doctorDetails;
        } else if ($role == 3) { //labtest user
            $labtestUser = new Application_Model_LabtestUser();
            $labtestUser->_userId = $userId;
            $labtestUserDetails = $labtestUser->getDetails();
            $this->view->labtest = $labtestUserDetails;
        } else if ($role == 4) { // cheing user
            $chekingUser = new Application_Model_ChekingUser();
            $chekingUser->_userId = $userId;
            $chekingUserDetails = $chekingUser->getDetails();
            $this->view->checking = $chekingUserDetails;
        } else if ($role == 5) {

            $reservationUser = new Application_Model_ReservationUser();
            $reservationUser->_userId = $userId;
            $reservationUserDetails = $reservationUser->getDetails();
            $this->view->reservation = $reservationUserDetails;
        }*/

        $this->view->userId = $userId;       
        $this->view->user = $userArray;        
    }

    public function addAction() {
        $form = $this->_makeForm();

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
                        $countryList = Application_Model_Utils::getCountryLIst();
                        $users->_country = $countryList[$form->getValue('country')];
                    }

                    $user->_roleId = $form->getValue('user_type');

                    $today = date('Y-m-d h:i:s');
                    $user->_createOn = $today;
                    $user->_changeOn = $today;

                    $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                    $user->_createBy = $submitUserId;
                    $user->_changeBy = $submitUserId;

                    $userId = $user->addUser();

                    if ($userId) {

                        $oldImageName = $form->getValue('image');

                        if (isset($oldImageName)) {
                            $temp = explode(".", $oldImageName);
                            $ext = $temp[count($temp) - 1];

                            $userNameArray = explode("@", $form->getValue('email'));

                            $imageName = $user->_image = $userNameArray[0] . '_' . $userId . '.' . $ext;

                            $fullPath = "images/users/" . $imageName;

                            copy("images/tmp/" . $oldImageName, $fullPath);

                            $user->_id = $userId;
                            $user->updateImage();
                        }

                        $this->view->message = array('success', 'User add sucessfully');
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

                    $this->view->errors = $errors;
                }
            }
        }

        $this->view->form = $form;
    }

    public function editAction() {

        $request = $this->getRequest();

        //get parameter from url	
        if ($request->getParam('id')) {
            $userId = $request->getParam('id');          
        } else {
            $this->_redirect('/users');
        }

        $countryList = Application_Model_Utils::getCountryLIst();
        $options['countrylist'] = $countryList;
       
        $form = new Application_Form_UserForm($options);
        $form->removeElement('email');
        $form->removeElement('password');
        $form->removeElement('repassword');
        $form->removeDisplayGroup('ldetails');
        
        if (Zend_Registry::get('role') != 'admin') {
            $form->removeElement('user_type');
        }
        
        $userDetails = $this->_makePopulateEditForm($userId);

        if ($request->isPost()) {

            if ($form->isValid($request->getPost())) {

                $user = new Application_Model_Users();
                $user->_id = $userId;
                
                if (Zend_Registry::get('role') == 'admin') {                
                    $user->_roleId = $form->getValue('user_type');
                } else {
                    $user->_roleId = Zend_Auth::getInstance()->getStorage()->read()->role_id;
                }
                               
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
                    $countryList = Application_Model_Utils::getCountryLIst();
                    $user->_country = $countryList[$form->getValue('country')];
                }

                $today = date('Y-m-d h:i:s');
                $user->_changeOn = $today;

                $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                $user->_changeBy = $submitUserId;

                if ($user->editUser()) {

                    $oldImageName = $form->getValue('image');

                    if (isset($oldImageName)) {
                        $temp = explode(".", $oldImageName);
                        $ext = $temp[count($temp) - 1];

                        $userNameArray = explode("@", $form->getValue('email'));

                        $imageName = $user->_image = $userNameArray[0] . '_' . $userId . '.' . $ext;

                        $fullPath = "images/users/" . $imageName;

                        copy("images/tmp/" . $oldImageName, $fullPath);

                        $user->updateImage();
                    }

                    $userDetails = $this->_makePopulateEditForm($userId);
                    $this->view->message = array('success', 'User edit successfully');
                }
            }
        }

        $form->populate($userDetails);
        $this->view->userId = $userId;
        $this->view->form = $form;
    }

    /**
     * Use for delete role form ajax request
     * @return (echo) true for successfull delete, false for unsuccessfull
     */
    public function deleteAction() {
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {
            $userId = $this->getRequest()->getParam('id');

            $users = new Application_Model_Users();
            $users->_id = $userId;

            $today = date('Y-m-d h:i:s');
            $user->_changeOn = $today;

            $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
            $user->_changeBy = $submitUserId;

            if ($users->deleteUser()) {
                echo true;
            } else {
                echo false;
            }
        }
    }

    /**
     * Use for change user loging details
     * Get doctor id form URL redirect to doctor if id is not availble
     * Get password change form and pass to view
     * Update new loging details to user table
     * Daily Details is not correct.
     */
    public function changepasswordAction() {
        $request = $this->getRequest();

        //get parameter from url	
        if ($request->getParam('id')) {
            $userId = $request->getParam('id');
        } else {
            $this->_redirect('/users');
        }

        $form = new Application_Form_ChangePasswordForm();

        $users = new Application_Model_Users();
        $users->_id = $userId;
        $userDetails = $users->getUserLoginDetails();

        $form->populate($userDetails);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $users->_email = $form->getValue('email');

                $prassword = $form->getValue('password');
                $repassword = $form->getValue('repassword');

                if ($users->checkUserName() && $prassword == $repassword) {
                    $users->_password = md5($prassword);

                    $users->updateLoginDetails();

                    $this->view->message = array('success', 'Update login details successfully');
                } else {
                    $errors = array();

                    if (!$users->checkUserName()) {
                        $errors['usernameE'] = array(
                            'status' => 'errors',
                            'text' => 'User name already exsist'
                        );
                    }

                    if ($prassword != $repassword) {
                        $errors['passwordE'] = array(
                            'status' => 'errors',
                            'text' => 'Password not match'
                        );
                    }

                    $this->view->errors = $errors;
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * Make form for add, edit user
     * remove capture, change submit button label, add user list
     * @return Application_Form_SingupForm 
     */
    private function _makeForm() {
        $countryList = Application_Model_Utils::getCountryLIst();

        $options['countrylist'] = $countryList;

        $form = new Application_Form_UserForm($options);
        
        return $form;
    }

    private function _makePopulateEditForm($userId) {
        $countryList = Application_Model_Utils::getCountryLIst();
        $users = new Application_Model_Users();
        $users->_id = $userId;
        $userDetails = $users->getUser();

        $userArray = array();
        $userArray['id'] = $userDetails['id'];
        $userArray['title'] = $userDetails['title'];
        $userArray['initials'] = $userDetails['initials'];
        $userArray['telno'] = $userDetails['tel_no'];
        $userArray['fname'] = $userDetails['first_name'];
        $userArray['lname'] = $userDetails['last_name'];
        $userArray['email'] = $userDetails['email'];
        $userArray['bday'] = $userDetails['bday'];
        $userArray['telno'] = $userDetails['tel_no'];
        $userArray['image'] = $userDetails['image'];
        $userArray['user_type'] = $userDetails['role_id'];

        if (isset($userDetails['address1']) && !empty($userDetails['address1'])) {
            $userArray['address1'] = $userDetails['address1'];
        }
        if (isset($userDetails['address2']) && !empty($userDetails['address2'])) {
            $userArray['address2'] = $userDetails['address2'];
        }
        if (isset($userDetails['city']) && !empty($userDetails['city'])) {
            $userArray['city'] = $userDetails['city'];
        }
        if (isset($userDetails['country']) && !empty($userDetails['country'])) {
            $userArray['country'] = array_search($userDetails['country'], $countryList);
        }

        return $userArray;
    }
    
    
    private function _getUserType($roleId) 
    {
        $role = null;
        
        switch ($roleId) {
            case 'admin': $role = 'Administrator';
                break;

            case 'doctor': $role = 'Doctor';
                break;

            case 'patient': $role = 'Patient';
                break;

            case 'lbuser': $role = 'Lab Test User';
                break;

            case 'chuser': $role = 'Checking User';
                break;

            case 'nmuser': $role = 'Normal User';
                break;

            case 'rsuser': $role = 'Reservation User';
                break;

            default:
                break;
        }
        
        return $role;
    }

}