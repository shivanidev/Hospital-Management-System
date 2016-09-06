<?php

class ReservationsController extends Zend_Controller_Action 
{

    public function init() 
    {
        /* Initialize action controller here */
    }

    public function indexAction() 
    {
        $reservation = new Application_Model_Reservation();
        $reservations = $reservation->getResrvations();

        $this->view->reservations = $reservations;
    }

    public function addAction()
    {        
        $room = new Application_Model_Room();
        $rooms = $room->getRooms();

        if ($rooms) {
                       
            $user = new Application_Model_Users();
            $patients = $user->getAllPatients();

            $options = array();
            
            //rooms list for rooms drop down
            foreach ($rooms as $room) {
                $options['rooms'][$room['id']] = $room['name'];
            }

            if ($patients) {            
                //patients list for patient drop down
                foreach ($patients as $patient) {
                    $options['patients'][$patient['id']] = $patient['first_name'] . " " . $patient['last_name'];
                }            
            }

            $form = new Application_Form_ReservationsForm($options);

            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->getRequest()->getPost())) {
                    $values = $form->getValues();                   

                    $reservation = new Application_Model_Reservation();
                    $reservation->_patient_id = $values['patient_id'];
                    $reservation->_room_id = $values['room_id'];
                    $reservation->_from_time = $values['from_date'];
                    $reservation->_to_time = $values['to_date'];
                    $reservation->_description = $values['description'];

                    $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                    
                    $reservation->_create_by = $submitUserId;
                    $reservation->_create_on = date("Y-m-d H:m:s");

                    $add_reservation = $reservation->addReservation();

                    if ($add_reservation) {
                        $this->view->message = array('success', 'Reservation added successfully');
                    } else {
                        $this->view->message = array('errors', 'Reservation add unsuccessful');
                    }
                }
            }

            $add_user_success = $this->getRequest()->getParam('success');

            if ($add_user_success == 1) {
                $this->view->message = array('success', 'User added sucessfully');
            }

            $this->view->form = $form;
            $this->view->room = true;
        } else {
            $this->view->room = false;
            $this->view->message = array('errors', 'Room not found. Please add room.');
        }
    }

    public function editAction() 
    {
        $request = $this->getRequest();

        //get parameter from url	
        if ($request->getParam('id')) {
            $reservationId = $request->getParam('id');
        } else {
            $this->_redirect('/reservations');
        }
             
        $room = new Application_Model_Room();
        $rooms = $room->getRooms();

        if ($rooms) {
           
            $options = array();
            
            //rooms list for rooms drop down
            foreach ($rooms as $room) {
                $options['rooms'][$room['id']] = $room['name'];
            }
             
            $form = new Application_Form_ReservationsForm($options);
            
            $form->removeElement('patient_id');
            
            $reservation = new Application_Model_Reservation();
            $reservation->_id = $reservationId;
            $reservationDetails = $reservation->getResrvation();
            $reservationDetails['from_date'] = date('Y-m-d', strtotime($reservationDetails['from_time']));
            $reservationDetails['to_date'] = date('Y-m-d', strtotime($reservationDetails['to_time']));
                      
            $form->populate($reservationDetails); 

            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->getRequest()->getPost())) {
                    $values = $form->getValues();                   
                                    
                    $reservation->_room_id = $values['room_id'];
                    $reservation->_from_time = $values['from_date'];
                    $reservation->_to_time = $values['to_date'];
                    $reservation->_description = $values['description'];

                    $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                    
                    $reservation->_change_by = $submitUserId;
                    $reservation->_change_on = date("Y-m-d H:m:s");

                    $update_reservation = $reservation->updateReservation();

                    if ($update_reservation) {
                        $this->view->message = array('success', 'Reservation edited sucessfully');
                    } else {
                        $this->view->message = array('errors', 'Reservation edit unsucessful');
                    }
                }
            }

            $this->view->form = $form;
            $this->view->room = true;
        } else {
            $this->view->room = false;
            $this->view->message = array('errors', 'Room not found. Please add room.');
        }
    }

    public function deleteAction() 
    {
        $id = $_POST['id'];

        if (isset($id)) {
            $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;

            $reservation = new Application_Model_Reservation();
            $reservation->_id = $id;
            $reservation->_change_by = $submitUserId;
            $reservation->_change_on = date("Y-m-d H:m:s");

            $delete = $reservation->deleteReservation();

            if ($delete) {
                return true;
            } else {
                return false;
            }
        } else {
            
        }
    }

    public function addnewuserAction() 
    {
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
                        $user->_country = $countryList[$form->getValue('country')];
                    }

                    $user->_roleId = 7;

                    $today = date('Y-m-d h:i:s');
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
                            $ext = $temp[count($temp) - 1];

                            $userNameArray = explode("@", $form->getValue('email'));

                            $imageName = $user->_image = $userNameArray[0] . '_' . $userId . '.' . $ext;

                            $fullPath = "images/users/" . $imageName;

                            copy("images/tmp/" . $oldImageName, $fullPath);

                            $user->_id = $userId;
                            $user->updateImage();
                        }

                        $this->_redirect('/reservations/add/success/1');
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

    private function _makeForm() 
    {
        $countryList = Application_Model_Utils::getCountryLIst();

        $options['countrylist'] = $countryList;

        $form = new Application_Form_SingupForm($options);

        $submit = $form->getElement('submit');
        $submit->setLabel('Submit');

        $form->removeElement('submit');
        $form->removeElement('captcha');

        $udetails = $form->getDisplayGroup('udetails');

        $udetails->addElement($submit);

        return $form;
    }

}

