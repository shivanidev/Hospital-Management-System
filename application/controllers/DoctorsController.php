<?php

class DoctorsController extends Zend_Controller_Action {

    public function init() 
    {
        /* Initialize action controller here */
    }

    /**
     * Get doctors details and pass to view 
     */
    public function indexAction() 
    {
        $doctors = new Application_Model_Doctors();
        $doctorList = $doctors->getDoctors();

        $doctorsArray = array();

        foreach ($doctorList as $doctor) {

            if (!isset($doctorsArray[$doctor['doctorID']])) {

                $name = $doctor['title'] . '. ' . $doctor['initials'] . ' '
                        . $doctor['first_name'] . ' ' . $doctor['last_name'];

                $doctorsArray[$doctor['doctorID']] = array();
                $doctorsArray[$doctor['doctorID']]['user_id'] = $doctor['userID'];
                $doctorsArray[$doctor['doctorID']]['name'] = $name;
                $doctorsArray[$doctor['doctorID']]['specialities'] = $doctor['special_title'];
            } else {
                $doctorsArray[$doctor['doctorID']]['specialities'] .= ', ' . $doctor['special_title'];
            }
        }

        $this->view->doctors = $doctorsArray;
    }

    /**
     * Show doctor details in view
     */
    public function viewAction() 
    {
        $request = $this->getRequest();

        //get parameter from url	
        if ($request->getParam('id')) {
            $userId = $request->getParam('id');
        } else {
            $this->_redirect('/doctors');
        }

        $doctors = new Application_Model_Doctors();
        $doctors->_userId = $userId;
        $doctorDetails = $doctors->getDoctor();

        $doctorId = $doctorDetails['doctor_id'];
        
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

        $doctorDetails['name'] = $doctorDetails['title'] . ' '
                . $doctorDetails['initials'] . ' '
                . $doctorDetails['fname'] . ' '
                . $doctorDetails['lname'];

        $doctorDetails['address'] = $doctorDetails['address1'] . ', '
                . $doctorDetails['address2'] . ', '
                . $doctorDetails['city'] . ', '
                . $doctorDetails['country'];

        $this->view->userId = $userId;
        $this->view->doctorId = $doctorId;
        $this->view->user = $doctorDetails;
    }

    /**
     * Use for add doctor details for database
     * Make form with sub forms 
     * Get values from form
     * Add user details for user table
     * Upload profile image to public/images/users 
     * Add doctor details for doctor table
     * Add spetiolities details for doctor_spe_map table
     * Add doctor week days details for doctor_time table
     * 
     */
    public function addAction() 
    {
        $form = $this->makeForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $users = new Application_Model_Users();

                $users->_id = null;
                $users->_email = $form->getValue('email');

                if (
                        $users->checkUserName()
                        && ($form->getValue('password') == $form->getValue('repassword'))
                ) {
                    //Insert user details for user table
                    $users->_password = md5($form->getValue('password'));
                    $users->_title = 'Dr';
                    $users->_initials = $form->getValue('initials');
                    $users->_fname = $form->getValue('fname');
                    $users->_lname = $form->getValue('lname');
                    $users->_bday = $form->getValue('bday');
                    $users->_telno = $form->getValue('telno');
                    $users->_address1 = $form->getValue('address1');
                    $users->_address2 = $form->getValue('address2');
                    $users->_city = $form->getValue('city');

                    if ($form->getValue('country')) {
                        $countryList = Application_Model_Utils::getCountryLIst();
                        $users->_country = $countryList[$form->getValue('country')];
                    }

                    $users->_roleId = '2';

                    $today = date('Y-m-d h:i:s');
                    $users->_createOn = $today;
                    $users->_changeOn = $today;

                    $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                    $users->_createBy = $submitUserId;
                    $users->_changeBy = $submitUserId;

                    $userId = $users->addUser();

                    if ($userId) {

                        //Upload profile image to public/images/users 
                        $oldImageName = $form->getValue('image');

                        if (isset($oldImageName)) {
                            $temp = explode(".", $oldImageName);
                            $ext = $temp[count($temp) - 1];

                            $userNameArray = explode("@", $form->getValue('email'));

                            $imageName = $users->_image = $userNameArray[0] . '_' . $userId . '.' . $ext;

                            $fullPath = "images/users/" . $imageName;

                            copy("images/tmp/" . $oldImageName, $fullPath);

                            $users->_id = $userId;
                            $users->updateImage();
                        }

                        //Insert doctor details for doctor table                        
                        $doctors = new Application_Model_Doctors();

                        $doctorSubForm = $form->getSubForm('doctor_sub_form');

                        $doctors->_userId = $userId;
                        $doctors->_degree = $doctorSubForm->getValue('degree');
                        $doctors->_payment = $doctorSubForm->getValue('payment');
                        $doctors->_description = $doctorSubForm->getValue('description');
                        $doctors->_createOn = $today;
                        $doctors->_createBy = $submitUserId;
                        $doctors->_changeOn = $today;
                        $doctors->_changeBy = $submitUserId;

                        $doctorId = $doctors->addDoctor();

                        //Insert spetiolities
                        $doctorSpecialitiesMap = new Application_Model_DoctorSpecialtiesMap();

                        $doctorSpecialitiesMap->_doctorId = $doctorId;

                        $spetiolityArray = $doctorSubForm->getValue('specialties');

                        foreach ($spetiolityArray as $speciality) {
                            $doctorSpecialitiesMap->_specialistId = $speciality;
                            $doctorSpecialitiesMap->addDoctorSpetiolity();
                        }

                        //Insert doctor times
                        $doctorTime = new Application_Model_DoctorTime();

                        $doctorTime->_doctorId = $doctorId;

                        $weekdays = $request->getPost('weekdays');

                        foreach ($weekdays as $key => $day) {

                            if (isset($day['val'])) {
                                $doctorTime->_day = $key;
                                $doctorTime->_fromTime = $day['from'];
                                $doctorTime->_toTime = $day['to'];

                                $doctorTime->addDoctorTime();
                            }
                        }

                        $this->view->message = array('success', 'Add doctor sucessfully');
                    }
                } else {

                    $errors = array();

                    if (!$users->checkUserName()) {
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

    /**
     * Use for edit doctor details for database
     * Make form with sub forms 
     * Get values from form
     * Add user details for user table
     * Upload profile image to public/images/users 
     * Delete current doctor details and add doctor details for doctor table
     * Delete current specialities and add spetiolities details for doctor_spe_map table
     * Delete current week days and add doctor week days details for doctor_time table
     */
    public function editAction() 
    {
        $request = $this->getRequest();

        //get parameter from url	
        if ($request->getParam('id')) {
            $userId = $request->getParam('id');
        } else {
            $this->_redirect('/doctors');
        }

        $doctorDetials = $this->makePopulateValues($userId);

        $doctorId = $doctorDetials['doctor_id'];

        $form = $this->_makeEditForm($doctorId);
        
        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $users = new Application_Model_Users();

                $users->_id = $userId;

                //Insert user details for user table                    
                $users->_title = 'Dr';
                $users->_initials = $form->getValue('initials');
                $users->_fname = $form->getValue('fname');
                $users->_lname = $form->getValue('lname');
                $users->_bday = $form->getValue('bday');
                $users->_telno = $form->getValue('telno');
                $users->_address1 = $form->getValue('address1');
                $users->_address2 = $form->getValue('address2');
                $users->_city = $form->getValue('city');

                if ($form->getValue('country') != 0) {
                    $countryList = Application_Model_Utils::getCountryLIst();
                    $users->_country = $countryList[$form->getValue('country')];
                }

                $users->_roleId = '2';

                $today = date('Y-m-d h:i:s');
                $users->_changeOn = $today;

                $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                $users->_changeBy = $submitUserId;

                if ($users->editUser()) {

                    //Upload profile image to public/images/users 
                    $oldImageName = $form->getValue('image');

                    if (isset($oldImageName)) {
                        $temp = explode(".", $oldImageName);
                        $ext = $temp[count($temp) - 1];

                        $userNameArray = explode("@", $form->getValue('email'));

                        $imageName = $users->_image = $userNameArray[0] . '_' . $userId . '.' . $ext;

                        $fullPath = "images/users/" . $imageName;

                        copy("images/tmp/" . $oldImageName, $fullPath);

                        $users->updateImage();
                    }

                    //Insert doctor details for doctor table
                    $doctors = new Application_Model_Doctors();

                    $doctorSubForm = $form->getSubForm('doctor_sub_form');

                    $doctors->_id = $doctorId;
                    $doctors->_degree = $doctorSubForm->getValue('degree');
                    $doctors->_payment = $doctorSubForm->getValue('payment');
                    $doctors->_description = $doctorSubForm->getValue('description');
                    $doctors->_changeOn = $today;
                    $doctors->_changeBy = $submitUserId;

                    $doctors->editDoctor();

                    //Insert spetiolities
                    $doctorSpecialitiesMap = new Application_Model_DoctorSpecialtiesMap();

                    $doctorSpecialitiesMap->_doctorId = $doctorId;
                    $doctorSpecialitiesMap->deleteDoctorSpetiolities();

                    $spetiolityArray = $doctorSubForm->getValue('specialties');

                    foreach ($spetiolityArray as $speciality) {
                        $doctorSpecialitiesMap->_specialistId = $speciality;
                        $doctorSpecialitiesMap->addDoctorSpetiolity();
                    }

                    //Insert doctor times
                    $doctorTime = new Application_Model_DoctorTime();

                    $doctorTime->_doctorId = $doctorId;
                    $doctorTime->deleteDoctorTimes();

                    $weekdays = $request->getPost('weekdays');

                    foreach ($weekdays as $key => $day) {

                        if (isset($day['val'])) {
                            $doctorTime->_day = $key;
                            $doctorTime->_fromTime = $day['from'];
                            $doctorTime->_toTime = $day['to'];

                            $doctorTime->addDoctorTime();
                        }
                    }
                    
                    $form = $this->_makeEditForm($doctorId);
                    
                    $this->view->message = array('success', 'Edit doctor sucessfully');
                }
            }
        }
        
        $form->populate($doctorDetials);   
                                      
        $this->view->form = $form;
        $this->view->userId = $userId;
        $this->view->doctorId = $doctorId;       
    }

    /**
     * Use for delete role form ajax request
     * @return (echo) true for successfull delete, false for unsuccessfull
     */
    public function deleteAction() 
    {
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
    public function changepasswordAction() 
    {
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

                    $this->view->message = array('success', 'Update loging details sucessfully');
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
     * Show details about channeling
     * Show channeling form
     * Add submitted data to session
     * Redirect to payment or signin
     */
    public function channelingAction() 
    {
        $request = $this->getRequest();

        //get parameter from url	
        if ($request->getParam('id')) {
            $userId = $request->getParam('id');
        } else {
            $this->_redirect('/doctors');
        }

        $doctors = new Application_Model_Doctors();
        $doctors->_userId = $userId;
        $doctorDetails = $doctors->getDoctor();
        $doctorId = $doctorDetails['doctor_id'];
        
        $doctorName = $doctorDetails['title'] . ' '
                . $doctorDetails['initials'] . ' '
                . $doctorDetails['fname'] . ' '
                . $doctorDetails['lname'];

        $doctorTime = new Application_Model_DoctorTime();
        $doctorTimeDetails = $doctorTime->getDoctorTimeDetails($doctorId);
              
        $datearray = array();

        foreach ($doctorTimeDetails as $day) {
            $date = date('N', strtotime($day['day']));
            if ($date == 7) {
                $date = 0;
            }

            array_push($datearray, $date);
        }

        $options = array();
        $options['legend'] = 'Channel Details';
        $options['label'] = 'Channeling Date';

        $form = new Application_Form_ChannelingForm($options);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $data = array();
                $data['doctor_id'] = $doctorId;
                $data['date'] = $form->getValue('date');
                $data['comment'] = $form->getValue('comment');
                $data['name'] = $doctorName;
                $data['payment'] = $doctorDetails['payment'];
                $data['times'] = $doctorTimeDetails;

                $session = new Zend_Session_Namespace('default');
                $session->doctorChanneling = $data;

                if (Zend_Auth::getInstance()->hasIdentity()) {
                    $this->_redirect('/payment');
                } else {
                    $this->_redirect('/signin');
                }
            }
        }

        $this->view->form = $form;
        $this->view->doctor = $doctorName;
        $this->view->payment = $doctorDetails['payment'];
        $this->view->doctorTime = $doctorTimeDetails;
        $this->view->dates = json_encode($datearray);
    }

    /**
     * Show confiermation details
     */
    public function confirmAction() 
    {
        $session = new Zend_Session_Namespace('default');

        if ($session->doctorChanneling) {
            $data = $session->doctorChanneling;
            unset($session->doctorChanneling);
        } else {
            $this->_redirect('/doctors');
        }

        $doctorPatientMap = new Application_Model_DoctorPatientMap();
        $doctorPatientMap->_doctorId = $data['doctor_id'];
        $doctorPatientMap->_channelDate = $data['date'];
        $doctorPatientMap->_comment = $data['comment'];

        $today = date('Y-m-d h:i:s');
        $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;

        $doctorPatientMap->_patientId = $submitUserId;
        $doctorPatientMap->_createOn = $today;
        $doctorPatientMap->_createBy = $submitUserId;
        $doctorPatientMap->_changeOn = $today;
        $doctorPatientMap->_changeBy = $submitUserId;

        $doctorPatientMap->addDoctorPatient();
        
        $pateint = new Application_Model_Patient();
        $pateint->_patientId = $submitUserId;
        $pateint->_type = 1;
        
        $pateint->addPatient();

        $channelday = lcfirst(date('l', strtotime($data['date'])));

        $timeData = null;

        foreach ($data['times'] as $time) {
            if ($time['day'] == $channelday) {
                $timeData = substr($time['from_time'], 0, 5)
                        . ' - ' . substr($time['to_time'], 0, 5);
            }
        }

        if ($timeData == NULL) {
            $timeData = '00:00 - 00:00';
        }

        $data['time'] = $timeData;

        $this->view->data = $data;
    }

    /**
     * Make form using singup form remove title, capture and change possion of submit button
     * Add doctor sub form and weekdays sub form
     * @return form 
     */
    private function makeForm($doctorId = null) 
    {
        $countryList = Application_Model_Utils::getCountryLIst();

        $options['countrylist'] = $countryList;

        $form = new Application_Form_SingupForm($options);

        $submit = $form->getElement('submit');

        $form->removeElement('title');
        $form->removeElement('captcha');
        $form->removeElement('submit');

        $specialties = new Application_Model_Specialties();
        $specialtiesDetials = $specialties->getSpecialtiesList();

        $suboptions['specialties'] = $specialtiesDetials;

        $doctorSubForm = new Application_Form_DoctorSubForm($suboptions);

        $timeoptions = array();

        if (isset($doctorId)) {
            $doctorTime = new Application_Model_DoctorTime();
            $doctorTimeDetails = $doctorTime->getDoctorTimeDetails($doctorId);

            $timeoptions['times'] = $doctorTimeDetails;
        } else {
            $timeoptions['times'] = null;
        }
       // print_r($timeoptions);
        $weekdaySubForm = new Application_Form_WeekdaysSubForm($timeoptions);
      
        $form->addSubForm($doctorSubForm, 'doctor_sub_form');
        $form->addSubForm($weekdaySubForm, 'weekday_sub_form');

        $submit->setLabel('Submit');
        $submit->setDecorators(
                array(
                    'ViewHelper',
                    array('HtmlTag',
                        array('tag' => 'div', 'class' => 'clear marl40')
                    )
                )
        );

        $form->addElement($submit);

        return $form;
    }

    private function _makeEditForm($doctorId)
    {
        $form = $this->makeForm($doctorId);

        $form->removeDisplayGroup('ldetails');
        $form->removeElement('email');
        $form->removeElement('password');
        $form->removeElement('repassword');
        
        return $form;
    }
    
    /**
     * Use for make array for populate edit form 
     * @param int $userId
     * @return array 
     */
    private function makePopulateValues($userId) 
    {
        $doctors = new Application_Model_Doctors();
        $doctors->_userId = $userId;
        $doctorDetails = $doctors->getDoctor();

        $countryList = Application_Model_Utils::getCountryLIst();
                     
        $doctorDetails['country'] = array_search($doctorDetails['country'], $countryList);
        
        $doctorId = $doctorDetails['doctor_id'];
        
        $specialties = new Application_Model_Specialties();
        $doctorSpecialities = $specialties->getDoctorSpecialtiesList($doctorId);

        $specialtiesArray = array();

        foreach ($doctorSpecialities as $speciality) {
            array_push($specialtiesArray, $speciality['id']);
        }

        $doctorDetails['specialties'] = $specialtiesArray;


        return $doctorDetails;
    }

}
