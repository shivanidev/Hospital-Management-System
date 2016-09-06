<?php

class CheckingController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    /*
     * Get checking details and pass to view
     */

    public function indexAction() {
        $checking = new Application_Model_Checking();
        $checkingList = $checking->getCheckings();
        $this->view->checkings = $checkingList;
    }

    /*
     * use for view a check-in
     */

    public function viewAction() {
        $request = $this->getRequest();

        $checking = new Application_Model_Checking();
        $chid = $request->getParam('id');
        $checking->_id = $chid;
        $checkingDetail = $checking->getChecking();
        $checkingTime = new Application_Model_CheckingTime();
        $checkingTime->_checking_id = $chid;
        $checkingTimeDetail = $checkingTime->getCheckingTime();
        $checkingDetail['weekdays'] = $checkingTimeDetail;

        $this->view->checkingDetail = $checkingDetail;
    }

    /*
     * use for add new checking record
     */

    public function addAction() {

        $form = $this->_makeForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $checking = new Application_Model_Checking();



                $checking->_cname = $form->getValue('name');
                $checking->_payment = $form->getValue('payment');
                $checking->_description = $form->getValue('description');
                $checking->_create_on = date("Y-m-d H:m:s");

                $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                $checking->_create_by = $submitUserId;

                if ($checking->checkName()) {
                    $chid = $checking->addChecking();
                    if ($chid) {
                        $checkingtime = new Application_Model_CheckingTime();
                        $checkingtime->_checking_id = $chid;

                        $weekdays = $request->getPost('weekdays');

                        foreach ($weekdays as $key => $day) {
                            if (isset($day['val'])) {
                                $checkingtime->_day = $key;
                                $checkingtime->_from_time = $day['from'];
                                $checkingtime->_to_time = $day['to'];

                                $checkingtime->addCheckingTime();
                            }
                        }
                        $this->view->message = array('success', 'checking detail added sucessfully');
                    } else {
                        $this->view->errors = array('error2' =>
                            array('status' => 'errors', 'text' => 'checking detail not added'));
                    }
                } else {
                    $this->view->errors = array('error1' =>
                        array('status' => 'errors', 'text' => 'checking name already exists'));
                }
            }
        }

        $this->view->form = $form;
    }

    /*
     * use for edit checking deatail
     */

    public function editAction() {
        $request = $this->getRequest();

        $checking = new Application_Model_Checking();
        $chid = $request->getParam('id');
        $checking->_id = $chid;
        $checkingDetail = $checking->getChecking();

        $form = $this->_makeForm($chid);

        if ($request->isPost()) {

            if ($form->isValid($request->getPost())) {

                $checking->_cname = $form->getValue('name');
                $checking->_payment = $form->getValue('payment');
                $checking->_description = $form->getValue('description');
                $checking->_change_on = date("Y-m-d H:m:s");

                $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                $checking->_change_by = $submitUserId;

                if ($checking->updateChecking()) {

                    $checkingtime = new Application_Model_CheckingTime();
                    $checkingtime->_checking_id = $chid;

                    $checkingtime->deleteCheckingTime();

                    $weekdays = $request->getPost('weekdays');
                    foreach ($weekdays as $key => $day) {
                        if (isset($day['val'])) {
                            $checkingtime->_day = $key;
                            $checkingtime->_from_time = $day['from'];
                            $checkingtime->_to_time = $day['to'];

                            $checkingtime->addCheckingTime();
                        }
                    }
                }
                $form = $this->_makeForm($chid);
                $this->view->message = array('success', 'Checkup detail updated sucessfully');
            }
        }
        $form->populate($checkingDetail);
        $this->view->form = $form;
    }

    public function deleteAction() {

        $request = $this->getRequest();
        $checking = new Application_Model_Checking();
        $chid = $request->getParam('id');
        $checking->_id = $chid;

        if ($checking->deleteChecking()) {
            echo "DELETECODE1";
        } else {
            echo "DELETECODE0";
        }
    }

    /*
     * use for book check-in
     */

    public function bookAction() {
        $request = $this->getRequest();

        //use for get necessary checking details
        $checking = new Application_Model_Checking();
        $chid = $request->getParam('id');
        $checking->_id = $chid;
        $checkingDetail = $checking->getChecking();
        $checkingTime = new Application_Model_CheckingTime();
        $checkingTime->_checking_id = $chid;
        $checkingTimeDetail = $checkingTime->getCheckingTime();

        $datearray = array();
        foreach ($checkingTimeDetail as $day) {
            $date = date('N', strtotime($day['day']));
            if ($date == 7) {
                $date = 0;
            }
            array_push($datearray, $date);
        }

        $options = array();
        $options['legend'] = 'Booking Details';
        $options['label'] = 'Booking Date';

        $form = new Application_Form_ChannelingForm($options);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $data = array();
                $data['checkingId'] = $chid;
                $data['name'] = $checkingDetail['name'];
                $data['times'] = $checkingTimeDetail;
                $data['date'] = $form->getValue('date');
                $data['comments'] = $form->getValue('comments');
                $data['payment'] = $checkingDetail['payment'];

                $session = new Zend_Session_Namespace('default');
                $session->checkingBook = $data;

                if (Zend_Auth::getInstance()->hasIdentity()) {
                    $this->_redirect('/payment');
                } else {
                    $this->_redirect('/signin');
                }
            }
        }

        $this->view->form = $form;
        $this->view->checking = $checkingDetail['name'];
        $this->view->payment = $checkingDetail['payment'];
        $this->view->checkingTime = $checkingTimeDetail;
        $this->view->dates = json_encode($datearray);
    }

    /**
     * Show confiermation details for checking book
     */
    public function confirmAction() {
        $session = new Zend_Session_Namespace('default');

        if ($session->checkingBook) {
            $data = $session->checkingBook;
            unset($session->checkingBook);
        } else {
            $this->_redirect('/checking');
        }

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

        $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;

        $checkingPatientMap = new Application_Model_CheckingPatientMap();
        $checkingPatientMap->_patient_id = $submitUserId;
        $checkingPatientMap->_date = $data['date'];
        $checkingPatientMap->_comment = $data['comments'];
        $checkingPatientMap->_checking_id = $data['checkingId'];
        $checkingPatientMap->_createBy = $submitUserId;
        $checkingPatientMap->_createOn = date("Y-m-d H:m:s");
        $checkingPatientMap->_changeBy = $submitUserId;
        $checkingPatientMap->_changeOn = date("Y-m-d H:m:s");

        $patient = new Application_Model_Patient();
        $patient->_patientId = $submitUserId;
        $patient->_type = 3;

        $patient->addPatient();

        if ($checkingPatientMap->addCheckingBooking()) {
            $this->view->message = array('success', 'Checkup booked sucessfully');
        }
    }

    private function _makeForm($checkingId = null) {
        $form = new Application_Form_CheckingForm();

        $submit = $form->getElement('submit');
        $form->removeElement('submit');

        if (isset($checkingId)) {
            $checkingTime = new Application_Model_CheckingTime();
            $checkingTime->_checking_id = $checkingId;
            $options['times'] = $checkingTime->getCheckingTime();
        } else {
            $options['times'] = null;
        }

        $weekdaySubForm = new Application_Form_WeekdaysSubForm($options);

        $form->addSubForm($weekdaySubForm, 'weekday_sub_form');
        $form->addElement($submit);

        return $form;
    }

}

