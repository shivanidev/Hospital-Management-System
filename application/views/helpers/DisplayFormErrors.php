<?php

class Zend_View_Helper_DisplayFormErrors {
	   
	function displayFormErrors($errors){
		if(count($errors) > 0){
			echo '<div class="error marginauto"><ul>';
			foreach($errors as $k => $err){
				foreach($err as $l => $msg){
					echo '<li>' . $msg . '</li>';
				}
			}
			echo '</ul></div>';
		}
	}
	
}

?>