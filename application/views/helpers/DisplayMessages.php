<?php 

class Zend_View_Helper_DisplayMessages 
{	
    public function displayMessages($messages = null)
    {
        if ($messages != null) {
            foreach ($messages as $key => $message) {				
                $text = $message['text'];
                $status = $message['status'];
                $class = '';

                switch ((string) $status) {
                    case 'success' :
                            $class = 'success';
                            break;
                    case 'errors' :
                            $class = 'errors';
                            break;
                }

                echo '<ul class="'.$class.'"><li>'.$text.'</li></ul>';			
            }			
        }
    }	
}
