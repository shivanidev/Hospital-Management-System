<?php

class Application_Form_ReservationsForm extends Zend_Form 
{

    /**
     * Form's set values and multy options
     * @var array
     */
    private $_options;

    /**
     * Initailise options arrays
     * @param $options
     */
    public function __construct($options = null) 
    {
        if (isset($options)) {
            $this->_options = $options;
        }
        parent::__construct();
        $this->setMethod('post');
    }

    public function init() 
    {        
        $options = $this->_options;
        
        $textDecorator = array(
            'ViewHelper',
            'Label',
            'Errors',
            array('HtmlTag',
                array('tag' => 'div', 'class' => 'formfield')
            )
        );

        $patient = new Zend_Form_Element_Select('patient_id');
        $patient->setLabel('Patient');       
        $patient->setDecorators($textDecorator);       
        if (isset($options['patients'])) {
            $patient->setMultiOptions($options['patients']);
        }

        $roomname = new Zend_Form_Element_Select('room_id');
        $roomname->setLabel('Room');       
        $roomname->setDecorators($textDecorator);
        if (isset($options['rooms'])) {
            $roomname->setMultiOptions($options['rooms']);
        }

        $fromdate = new ZendX_JQuery_Form_Element_DatePicker('from_date');
        $fromdate->setLabel('From Date');
        $fromdate->setRequired(true);
        $fromdate->addValidator('date');       
        $fromdate->setJQueryParam('dateFormat', 'yy-mm-dd');
        $fromdate->setJQueryParam('changeMonth', true);
        $fromdate->setJQueryParam('changeYear', true);
        $fromdate->setJQueryParam('yearRange', '1900:2012');
        $fromdate->class = 'w150';
        $fromdate->setDecorators(
                array(
                    'UiWidgetElement',
                    'Label',
                    'Errors',
                    array('HtmlTag',
                        array('tag' => 'div', 'class' => 'formfield')
                    )
                )
        );

        $todate = new ZendX_JQuery_Form_Element_DatePicker('to_date');
        $todate->setLabel('To Date');
        $todate->setRequired(true);
        $todate->addValidator('date');       
        $todate->setJQueryParam('dateFormat', 'yy-mm-dd');
        $todate->setJQueryParam('changeMonth', true);
        $todate->setJQueryParam('changeYear', true);
        $todate->setJQueryParam('yearRange', '1900:2012');
        $todate->class = 'w150';
        $todate->setDecorators(
                array(
                    'UiWidgetElement',
                    'Label',
                    'Errors',
                    array('HtmlTag',
                        array('tag' => 'div', 'class' => 'formfield')
                    )
                )
        );

        $description = new Zend_Form_Element_Textarea('description ');
        $description->setLabel('Description');        
        $description->setDecorators($textDecorator);
       
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');
        $submit->class = 'button blue';

        $this->addElements(
            array(
                $patient,                    
                $roomname,
                $fromdate,
                $todate,
                $description,
                $submit
            )
        );
        
        $this->setDecorators(
            array(
                'FormElements',
                array(
                    'Fieldset',
                    array(
                        'legend' => 'Reservation Details'
                    )
                ),
                'Form'
            )
        );
    }

}