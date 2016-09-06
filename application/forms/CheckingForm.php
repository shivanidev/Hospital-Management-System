<?php

class Application_Form_CheckingForm extends Zend_Form {
   

    public function init() {
        
        $textDecorator = array(
            'ViewHelper',
            'Label',
            'Errors',
            array('HtmlTag',
                array('tag' => 'div', 'class' => 'formfield')
            )
        );

        $name = new Zend_Form_Element_Text("name");
        $name->setLabel("Name");
        $name->setRequired(true);
        $name->setDecorators($textDecorator);

        $payment = new Zend_Form_Element_Text("payment");
        $payment->setLabel("Payment (SLR)");
        $payment->setRequired(true);
        $payment->addValidator('Float');
        $payment->setDecorators($textDecorator);

        $description = new Zend_Form_Element_Textarea("description");
        $description->setLabel("Description");
        $description->setDecorators($textDecorator);
              
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");
        $submit->class = 'button blue';
        $submit->setDecorators(
                array(
                    'ViewHelper',
                    array('HtmlTag',
                        array('tag' => 'div', 'class' => 'clear marl40')
                    )
                )
        );   
        
        $this->addDisplayGroup( 
           array(
                $name,
                $payment,
                $description              
            ), 
            'udetails',
            array(
                'legend' => 'Checkup Details',
                'decorators' => array(
                    'FormElements', 
                    array('Fieldset', array('class' => 'sub floatleft'))                       
                )         
            )
        );
        
        $this->addElement($submit);
    }

}

