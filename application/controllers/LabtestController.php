<?php

class LabtestController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    /*
     * Get labtest details and pass to view
     */

    public function indexAction() {
        $labtest = new Application_Model_Labtest();
        $labtestList = $labtest->getLabtests();
        $this->view->labtests = $labtestList;
    }

    /*
     * use for view a check-in
     */

    public function viewAction() {
        $request = $this->getRequest();

        $labtest = new Application_Model_Labtest();
        $chid = $request->getParam('id');
        $labtest->_id = $chid;
        $labtestDetail = $labtest->getLabtest();
        $labtestTime = new Application_Model_LabtestTime();
        $labtestTime->_labtest_id = $chid;
        $labtestTimeDetail = $labtestTime->getLabtestTime();
        $labtestDetail['weekdays'] = $labtestTimeDetail;

        $this->view->labtestDetail = $labtestDetail;
    }

    /*
     * use for add new labtest record
     */

    public function addAction() {

        $form = $this->_makeForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $labtest = new Application_Model_Labtest();

                $labtest->_lname = $form->getValue('name');
                $labtest->_payment = $form->getValue('payment');
                $labtest->_description = $form->getValue('description');
                $labtest->_create_on = date("Y-m-d H:m:s");

                $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                $labtest->_create_by = $submitUserId;

                if ($labtest->checkName()) {
                    $chid = $labtest->addLabtest();
                    if ($chid) {
                        $labtesttime = new Application_Model_LabtestTime();
                        $labtesttime->_labtest_id = $chid;

                        $weekdays = $request->getPost('weekdays');

                        foreach ($weekdays as $key => $day) {
                            if (isset($day['val'])) {
                                $labtesttime->_day = $key;
                                $labtesttime->_from_time = $day['from'];
                                $labtesttime->_to_time = $day['to'];

                                $labtesttime->addLabtestTime();
                            }
                        }
                    } else {
                        $this->view->errors = array('error2' =>
                            array('status' => 'errors', 'text' => 'labtest detail not added'));
                    }

                    $this->view->message = array('success', 'Lab Test details added sucessfully');
                } else {
                    $this->view->errors = array('error1' =>
                        array('status' => 'errors', 'text' => 'labtest name already exists'));
                }
            }
        }

        $this->view->form = $form;
    }

    /*
     * use for edit labtest deatail
     */

    public function editAction() {
        $request = $this->getRequest();

        $labtest = new Application_Model_Labtest;
        $chid = $request->getParam('id');
        $labtest->_id = $chid;
        $labtestDetail = $labtest->getLabtest();

        $form = $this->_makeForm($chid);

        if ($request->isPost()) {

            if ($form->isValid($request->getPost())) {

                $labtest->_cname = $form->getValue('name');
                $labtest->_payment = $form->getValue('payment');
                $labtest->_description = $form->getValue('description');
                $labtest->_change_on = date("Y-m-d H:m:s");

                $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
                $labtest->_change_by = $submitUserId;

                if ($labtest->updateLabtest()) {

                    $labtesttime = new Application_Model_LabtestTime();
                    $labtesttime->_labtest_id = $chid;

                    $labtesttime->deleteLabtestTime();

                    $weekdays = $request->getPost('weekdays');
                    foreach ($weekdays as $key => $day) {
                        if (isset($day['val'])) {
                            $labtesttime->_day = $key;
                            $labtesttime->_from_time = $day['from'];
                            $labtesttime->_to_time = $day['to'];

                            $labtesttime->addLabtestTime();
                        }
                    }
                }

                $form = $this->_makeForm($chid);
                $this->view->message = array('success', 'Lab Test details updated sucessfully');
            }
        }
        $form->populate($labtestDetail);
        $this->view->form = $form;
    }

    public function deleteAction() {

        $request = $this->getRequest();
        $labtest = new Application_Model_Labtest();
        $chid = $request->getParam('id');
        $labtest->_id = $chid;

        if ($labtest->deleteLabtest()) {
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

        //use for get necessary labtest details
        $labtest = new Application_Model_Labtest();
        $chid = $request->getParam('id');
        $labtest->_id = $chid;
        $labtestDetail = $labtest->getLabtest();
        $labtestTime = new Application_Model_LabtestTime();
        $labtestTime->_labtest_id = $chid;
        $labtestTimeDetail = $labtestTime->getLabtestTime();

        $datearray = array();
        foreach ($labtestTimeDetail as $day) {
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
                $data['labtestId'] = $chid;
                $data['name'] = $labtestDetail['name'];
                $data['times'] = $labtestTimeDetail;
                $data['date'] = $form->getValue('date');
                $data['comments'] = $form->getValue('comments');
                $data['payment'] = $labtestDetail['payment'];

                $session = new Zend_Session_Namespace('default');
                $session->labtestBook = $data;

                if (Zend_Auth::getInstance()->hasIdentity()) {
                    $this->_redirect('/payment');
                } else {
                    $this->_redirect('/signin');
                }
            }
        }

        $this->view->form = $form;
        $this->view->labtest = $labtestDetail['name'];
        $this->view->payment = $labtestDetail['payment'];
        $this->view->labtestTime = $labtestTimeDetail;
        $this->view->dates = json_encode($datearray);
    }

    /**
     * Show confiermation details for labtest book
     */
    public function confirmAction() {
        $session = new Zend_Session_Namespace('default');

        if ($session->labtestBook) {
            $data = $session->labtestBook;
            unset($session->labtestBook);
        } else {
            $this->_redirect('/labtest');
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

        $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;

        $labtestPatientMap = new Application_Model_LabtestPatientMap();
        $labtestPatientMap->_patientId = $submitUserId;
        $labtestPatientMap->_date = $data['date'];
        $labtestPatientMap->_comment = $data['comments'];
        $labtestPatientMap->_lab_test_id = $data['labtestId'];
        $labtestPatientMap->_createBy = $submitUserId;
        $labtestPatientMap->_createOn = date("Y-m-d H:m:s");
        $labtestPatientMap->_changeBy = $submitUserId;
        $labtestPatientMap->_changeOn = date("Y-m-d H:m:s");

        $pateint = new Application_Model_Patient();
        $pateint->_patientId = $submitUserId;
        $pateint->_type = 2;

        $pateint->addPatient();


        if ($labtestPatientMap->addLabtestBooking()) {
            $this->view->message = array('success', 'labtest booked sucessfully');
        }

        $this->view->data = $data;
    }

    private function _makeForm($labtestId = null) {
        $form = new Application_Form_LabtestForm();

        $submit = $form->getElement('submit');
        $form->removeElement('submit');

        if (isset($labtestId)) {
            $labtestTime = new Application_Model_LabtestTime();
            $labtestTime->_labtest_id = $labtestId;
            $options['times'] = $labtestTime->getLabtestTime();
        } else {
            $options['times'] = null;
        }

        $weekdaySubForm = new Application_Form_WeekdaysSubForm($options);

        $form->addSubForm($weekdaySubForm, 'weekday_sub_form');
        $form->addElement($submit);

        return $form;
    }

}

