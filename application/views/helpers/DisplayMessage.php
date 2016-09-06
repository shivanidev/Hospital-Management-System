<?php

class Zend_View_Helper_DisplayMessage
{	   
    
    public function displayMessage($message) 
    {
        if (isset($message)) {        
            echo '<div class="'. $message[0] .' mart10"><ul><li>'. $message[1] .'</li></ul></div>';           
        }
    }
	
}
