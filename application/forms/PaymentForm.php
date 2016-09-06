<?php

class Application_Form_PaymentForm extends Zend_Form
{

    public function init()
    {
        $cardNumber = new Zend_Form_Element_Text('card_number');
        $cardNumber->setLabel('Card Number');
        
        $expriDate = new Zend_Form_Element_Text('expri_date');
        $expriDate->setLabel('Expri Date');
        
        $securityCode = new Zend_Form_Element_Text('security_code');
        $securityCode->setLabel('Security Code');
        
        $pay = new Zend_Form_Element_Submit('pay');
        $pay->setLabel('Pay');
        
        $this->addElements(
            array(
                $cardNumber, 
                $expriDate, 
                $securityCode,
                $pay
            )
        );
        
        $this->setDecorators(
            array(
                'FormElements',
                array(
                    'Fieldset',
                    array(
                        'legend' => 'Visa/Master'
                    )
                ),
                'Form'
            )
        );
        
    }
    
}
