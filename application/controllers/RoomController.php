<?php

class RoomController extends Zend_Controller_Action 
{
    public function init() 
    {
        /* Initialize action controller here */
    }

    public function indexAction() 
    {
        $room = new Application_Model_Room();

        $room_list = $room->getRooms();
        $this->view->room_list = $room_list;
    }

    public function addAction()
    {
        $form = new Application_Form_RoomForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {

                $room = new Application_Model_Room();

                $room->_roomname = $form->getValue('name');
                $room->_quantity = $form->getValue('quantity');
                $room->_payment = $form->getValue('payment');

                $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;

                $room->_create_by = $submitUserId;
                $room->_create_on = date("Y-m-d H:m:s");

                $addroom = $room->addRoom();

                if ($addroom) {
                    $this->view->message = array('success', 'Room added sucessfully');
                } else {
                    $this->view->message = array('error', 'Room add unsucessfull');
                }
            }
        }

        $this->view->form = $form;
    }

    public function editAction() 
    {
        $request = $this->getRequest();

        //get parameter from url	
        if ($request->getParam('id')) {
            $roomId = $request->getParam('id');
        } else {
            $this->_redirect('/room');
        }
       
        $room = new Application_Model_Room();
        $room->_id = $roomId;
        $roomDetails = $room->getRoom();
        $form = new Application_Form_RoomForm();
        
        $form->populate($roomDetails);

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $room->_roomname = $form->getValue('name');
                $room->_quantity = $form->getValue('quantity');
                $room->_payment = $form->getValue('payment');

                $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;

                $room->_change_by = $submitUserId;
                $room->_change_on = date("Y-m-d H:m:s");

                $updateroom = $room->updateRoom();

                if ($updateroom) {
                    $this->view->message = array('success', 'Room edited sucessfully');
                } else {
                    $this->view->message = array('error', 'Room edit unsucessfull');
                }
            }
        }

        $this->view->form = $form;
      
    }

    public function deleteAction() 
    {
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {
            $roomId = $this->getRequest()->getParam('id');
            
            $submitUserId = Zend_Auth::getInstance()->getStorage()->read()->id;
            
            $room = new Application_Model_Room();
            $room->_id = $roomId;
            $room->_change_by = $submitUserId;
            $room->_change_on = date("Y-m-d H:m:s");

            $delete = $room->deleteRoom();
                        
            if ($delete) {
                echo true;
            } else {
                echo false;
            }
        }
        
       
    }

}

