<?php

class Application_Form_RoomForm extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $options = $this->_options;

        $textDecorator =  array(
        	'ViewHelper',                
            'Label',
            'Errors',
            array('HtmlTag',
            	array('tag' => 'div', 'class' => 'formfield')
            )
        );
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name');       
        $name->setRequired(true);
       	$name->addFilter('StringTrim');        
        $name->setDecorators($textDecorator);
               
        $quantity = new Zend_Form_Element_Text('quantity');
        $quantity->setLabel('Quantity');       
        $quantity->setRequired(true);
       	$quantity->addFilter('StringTrim');
        $quantity->addValidator('Digits');
        $quantity->setDecorators($textDecorator);
                
        $payment = new Zend_Form_Element_Text('payment');
        $payment->setLabel('Payment (SLR)');        
        $payment->setRequired(true);
       	$payment->addFilter('StringTrim');   
        $payment->addValidator('Digits');
        $payment->setDecorators($textDecorator);
              
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');
        $submit->class = 'button blue';
        
        $this->addElements(array($name,	$quantity, $payment, $submit));
        
        $this->setDecorators(
            array(
                'FormElements',
                array(
                    'Fieldset',
                    array(
                        'legend' => 'Room Details'
                    )
                ),
                'Form'
            )
        );
        
    }

}