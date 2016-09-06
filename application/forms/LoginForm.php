<?php

class Application_Form_LoginForm extends Zend_Form
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
       
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Sign In');
        $submit->setDescription('<div id="signup_link"><a href="../signup">Sign Up</a></div>');
        $submit->class = 'button blue';
        $submit->setDecorators(
            array(
                'ViewHelper',                
                array('HtmlTag',
                    array('tag' => 'div', 'class' => 'floatright mart10')
                ),
                array('Description', 
                    array('escape' => false, 'tag' => false)
                )
            )
        );	
              
        $this->addElements(array($email, $password, $submit));        
        
        $this->setDecorators(
            array(
                'FormElements',
                array(
                    'Fieldset',
                    array(
                        'legend' => 'Login'
                    )
                ),
                'Form'
            )
        );
    }

}