<?php

class Application_Form_ChangePasswordForm extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
       
      	$email = new Zend_Form_Element_Text('email');
       	$email->setLabel('User Name (ex: pat@example.com)');
       	$email->setRequired(true);
       	$email->addFilter('StringTrim');
       	$email->addValidator('EmailAddress');      
        $email->setDecorators(
           array(
                'ViewHelper',                
                'Label',
                'Errors',
                array('HtmlTag',
                    array('tag' => 'div', 'class' => 'formfield')
                )
            )
        );	
       
       	$password = new Zend_Form_Element_Password('password');
       	$password->setLabel('Password');
       	$password->setRequired(true);
       	$password->addFilter('StringTrim');
       	$password->addValidator(
            'StringLength', 
            false, 
            array('min' => 6, 'max' => 30)
        );               
        $password->setDecorators(
            array(
                'ViewHelper',                
                'Label',
                'Errors',
                array('HtmlTag',
                    array('tag' => 'div', 'class' => 'formfield')
                )
            )
        );	
        
        $repassword = new Zend_Form_Element_Password('repassword');
       	$repassword->setLabel('Re-enter Password');
       	$repassword->setRequired(true);
       	$repassword->addFilter('StringTrim');
       	$repassword->addValidator(
            'StringLength', 
            false, 
            array('min' => 6, 'max' => 30)
        );               
        $repassword->setDecorators(
            array(
                'ViewHelper',                
                'Label',
                'Errors',
                array('HtmlTag',
                    array('tag' => 'div', 'class' => 'formfield')
                )
            )
        );	
       
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');       
        $submit->class = 'button blue';
        $submit->setDecorators(
            array(
                'ViewHelper',                
                array('HtmlTag',
                    array('tag' => 'div', 'class' => 'floatleft mart25')
                )
            )
        );	
              
        $this->addElements(array($email, $password, $repassword, $submit));        
        
        $this->setDecorators(
            array(
                'FormElements',
                array(
                    'Fieldset',
                    array(
                        'legend' => 'Change Password'
                    )
                ),
                'Form'
            )
        );
    }

}