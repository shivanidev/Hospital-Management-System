<?php

class ChatController extends Zend_Controller_Action {

    private $_session = null;

    public function init() {

        $this->_session = new Zend_Session_Namespace('default');

        if (!isset($this->_session->chatHistory)) {
            $this->_session->chatHistory = array();
        }

        if (!isset($this->_session->openChatBoxes)) {
            $this->_session->openChatBoxes = array();
        }
    }

    public function indexAction() {


        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/signin');
        }

        $fname = Zend_Auth::getInstance()->getStorage()->read()->first_name;
        $id = Zend_Auth::getInstance()->getStorage()->read()->id;
        $lname = Zend_Auth::getInstance()->getStorage()->read()->last_name;
        $roleId = Zend_Auth::getInstance()->getStorage()->read()->role_id;

        //session_start();
        $_SESSION['username'] = $fname . '' . $lname; // Must be already set
        $chatModel = new Application_Model_Chat();


        $users;
        switch ($roleId) {

            case 1: //admin
                $users = $chatModel->getUsers($id, 'admin');
                break;
            case 2: //doctor
                $users = $chatModel->getUsers($id, 'doctor');
                break;
            case 3: //lab test user
                $users = $chatModel->getUsers($id, 'lbuser');
                break;
            case 4: //checking user
                $users = $chatModel->getUsers($id, 'chuser');
                break;
            case 5: // reservation user
                $users = $chatModel->getUsers($id, 'rsuser');
                break;
            case 7: //patient
                $users = $chatModel->getUsers($id, 'patient');
                break;
        }

        $usersArray = array();
        foreach ($users as $user) {

            switch ($user['role']) {
                case 'admin' :
                    $user['role'] = 'Administrator';
                    break;
                case 'chuser' :
                    $user['role'] = 'Checking User';
                    break;
                case 'lbuser' :
                    $user['role'] = 'Lab Test User';
                    break;
                case 'rsuser' :
                    $user['role'] = 'Reservation User';
                    break;
            }
            $usersArray[] = $user;
        }

        //print_r(session_save_path());

        $this->view->users = $usersArray;
    }

    public function chatheartbeatAction() {
        $chatModel = new Application_Model_Chat();
        $chatModel->_chatto = $_SESSION['username'];
        $chatData = $chatModel->getChatData();

        $items = '';
        $chatBoxes = array();



        foreach ($chatData as $chat) {

            if (!isset($_SESSION['openChatBoxes'][$chat['from']]) && isset($_SESSION['chatHistory'][$chat['from']])) {
                $items = $_SESSION['chatHistory'][$chat['from']];
            }

            $chat['message'] = $this->sanitize($chat['message']);
            $items .= <<<EOD
          {
          "s": "0",
          "f": "{$chat['from']}",
          "m": "{$chat['message']}"
          },
EOD;
            if (!isset($_SESSION['chatHistory'][$chat['from']])) {
                $_SESSION['chatHistory'][$chat['from']] = '';
            }

            $_SESSION['chatHistory'][$chat['from']] .= <<<EOD
          {
          "s": "0",
          "f": "{$chat['from']}",
          "m": "{$chat['message']}"
          },
EOD;
            unset($_SESSION['tsChatBoxes'][$chat['from']]);
            $_SESSION['openChatBoxes'][$chat['from']] = $chat['sent'];
        }





        if (!empty($_SESSION['openChatBoxes'])) {
            foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
                if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
                    $now = time() - strtotime($time);
                    $time = date('g:iA M dS', strtotime($time));

                    $message = "Sent at $time";
                    if ($now > 180) {
                        $items .= <<<EOD
          {
          "s": "2",
          "f": "$chatbox",
          "m": "{$message}"
          },
EOD;

                        if (!isset($_SESSION['chatHistory'][$chatbox])) {
                            $_SESSION['chatHistory'][$chatbox] = '';
                        }

                        $_SESSION['chatHistory'][$chatbox] .= <<<EOD
          {
          "s": "2",
          "f": "$chatbox",
          "m": "{$message}"
          },
EOD;
                        $_SESSION['tsChatBoxes'][$chatbox] = 1;
                    }
                }
            }
        }

        $chatModel->_chatto = $_SESSION['username'];
        $chatModel->updateChat();
        // echo $_SESSION['username'];
        if ($items != '') {
            $items = substr($items, 0, -1);
        }

        header('Content-type: application/json');
        echo '{"items": [' . $items . ']}';



        exit(0);
    }

    public function sendchatAction() {


        $from = $_SESSION['username'];
        $to = $_POST['to'];
        $message = $_POST['message'];

        $_SESSION['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());

        $messagesan = $this->sanitize($message);
        if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
            $_SESSION['chatHistory'][$_POST['to']] = '';
        }

        $_SESSION['chatHistory'][$_POST['to']] .= '
					   {
			"s": "1",
			"f": "{$to}",
			"m": "{$messagesan}"
	   },';



        unset($_SESSION['tsChatBoxes'][$_POST['to']]);

        $chat = new Application_Model_Chat();
        $chat->_chatfrom = $from;
        $chat->_chatto = $to;
        $chat->_message = $message;
        $chat->_sent = date('Y-m-d H:i:s');

        $chat->addChatMessage();
        echo "1";
        exit(0);
    }

    public function closechatAction() {

        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {

            $chatbox = $this->getRequest()->getParam('chatbox');

            // unset($this->_session->openChatBoxes[$chatbox]);
            unset($_SESSION['openChatBoxes'][$chatbox]);
            echo "1";
            exit(0);
        }
    }

    public function startchatsessionAction() {

        $items = '';
        if (!empty($_SESSION['openChatBoxes'])) {
            foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
                $items .= $this->chatBoxSession($chatbox);
            }
        }

        if ($items != '') {
            $items = substr($items, 0, -1);
        }

        header('Content-type: application/json');

        echo '{"username":"' . $_SESSION['username'] . '","items":[' . $items . ']}';

        exit(0);
    }

    private function sanitize($text) {
        $text = htmlspecialchars($text, ENT_QUOTES);
        $text = str_replace("\n\r", "\n", $text);
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\n", "<br>", $text);
        return $text;
    }

    private function chatBoxSession($chatbox) {

        $items = '';

        if (isset($_SESSION['chatHistory'][$chatbox])) {
            $items = $_SESSION['chatHistory'][$chatbox];
        }

        return $items;
    }

}

