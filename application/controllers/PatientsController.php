<?php

class PatientsController extends Zend_Controller_Action 
{
    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() 
    {
        $users = new Application_Model_Users();
        $currentUser = Zend_Auth::getInstance()->getStorage()->read();
        $roleId = $currentUser->role_id;
        $userId = $currentUser->id;

        switch ($roleId) {

            case 1:  //admin
                $patientList = $users->getPatients('admin');
                break;

            case 2: //doctor
                $patientList = $users->getPatients('doctor', $userId);
                break;

            case 3: //labtest user
                $patientList = $users->getPatients('lbuser');
                break;

            case 4: //checking user
                $patientList = $users->getPatients('chuser');
                break;
        }

        $checkedList = array();
        $uncheckdList = array();
        foreach ($patientList as $patient) {

            if ($patient['checkedSum'] == 0) {
                $checkedList[] = $patient;
            } else {
                $uncheckdList[] = $patient;
            }
        }

        $this->view->patientCheckingList = $checkedList;
        $this->view->patientUncheckingList = $uncheckdList;
    }

    public function viewAction() 
    {
        $request = $this->getRequest();

        //get parameter from url	
        if ($request->getParam('id')) {
            $patientId = $request->getParam('id');
        } else {
            $this->_redirect('/patients');
        }

        $users = new Application_Model_Users();
        $users->_id = $patientId;
        $patientDetails = $users->getUser();       
        
        $DoctorPatientMap = new Application_Model_DoctorPatientMap();
        $DoctorPatientMap->_patientId = $patientId;
        $channelList = $DoctorPatientMap->getChanelDoctorsList();  
        
        $labtestPatientMap = new Application_Model_LabtestPatientMap();
        $labtestPatientMap->_patientId = $patientId;
        $labTestList = $labtestPatientMap->getLabTestList();

        $checkingPatienMap = new Application_Model_CheckingPatientMap();
        $checkingPatienMap->_patientId = $patientId;
        $checkingList = $checkingPatienMap->getPatietCheckingList();

        $patientDetails['name'] = $patientDetails['title'] . ' '
                . $patientDetails['initials'] . ' '
                . $patientDetails['first_name'] . ' '
                . $patientDetails['last_name'];

        $patientDetails['address'] = $patientDetails['address1'] . ', '
                . $patientDetails['address2'] . ', '
                . $patientDetails['city'] . ', '
                . $patientDetails['country'];

        //view data arrays
        $this->view->currentUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
        $this->view->currentUserRole = Zend_Auth::getInstance()->getStorage()->read()->role_id;
        
        $this->view->user = $patientDetails;
        $this->view->channelList = $channelList;
        $this->view->labTestList = $labTestList;
        $this->view->checkingList = $checkingList;
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

    public function checkingAction() 
    {       
        $request = $this->getRequest();
        
         //get parameter from url	
        if ($request->getParam('id')) {
            $id = $request->getParam('id');
        } else {
            $this->_redirect('/patients');
        }
            
        $form = new Application_Form_PatientCheckingForm();
        
        $patientModel = new Application_Model_Patient();
        $patientModel->_id = $id;
        $patientDetails = $patientModel->getPatient();
        
        $form->populate($patientDetails);
      
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
               
                //add Checking status
                $patientModel->_status = 0;
                $patientModel->_note = $form->getValue('note');               

                //note add date
                $today = date('Y-m-d h:i:s');
                $patientModel->_date = $today;
               
                $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                $patientModel->_changedBy = $submitUserId;

                if ($patientModel->updatePatient()) {
                    $this->view->message = array('success', 'checking detail added sucessfully');
                    $this->_redirect('/patients');
                }
            }
        }

        $this->view->form = $form;
    }

    //geting patinet list
    private function getPatietList($patientList) 
    {
        $patientsArray = array();

        foreach ($patientList as $patient) {

            if (!isset($patientsArray[$patient['patientID']])) {

                $name = $patient['title'] . '. ' . $patient['initials'] . ' '
                        . $patient['first_name'] . ' ' . $patient['last_name'];

                $patientsArray[$patient['patientID']] = array();
                $patientsArray[$patient['patientID']]['patientID'] = $patient['patientID'];
                $patientsArray[$patient['patientID']]['name'] = $name;
            }
        }
        return $patientsArray;
    }
        
    /**
     * Use for make array for populate edit form 
     * @param int $patinet
     * @return array 
     */
    private function makePopulateValues($patinetId) 
    {
        $patinet = new Application_Model_Users();
        $patinet->_id = $patinetId;
        $patinetDetails = $patinet->getPatient();

        return $patinetDetails;
    } 
        
}

