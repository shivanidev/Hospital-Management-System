<?php
class Application_Form_DoctorSubForm extends Zend_Form_SubForm
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
        $this->setMethod('post');
            
    	$options = $this->_options;
    	
    	$degree = new Zend_Form_Element_Text('degree');
       	$degree->setLabel('Degree');       
       	$degree->addFilter('StringTrim');
        $degree->setDecorators(
            array(
                'ViewHelper',                
                'Label',                
                array('HtmlTag',
                    array('tag' => 'div', 'class' => 'formfield')
                )
            )
        );	
        
        $payment = new Zend_Form_Element_Text('payment');
       	$payment->setLabel('Payment per patient (SLR)');    
       	$payment->setRequired(true);   
       	$payment->addFilter('StringTrim');
       	$payment->addValidator('Float');
        $payment->setDecorators(
            array(
                'ViewHelper',                
                'Label',
            	'Errors',                
                array('HtmlTag',
                    array('tag' => 'div', 'class' => 'formfield')
                )
            )
        );
        
        $specialties = new Zend_Form_Element_Multiselect('specialties');
        $specialties->setLabel('Specialties');
        $specialties->setMultiOptions($options['specialties']);
        $specialties->setDecorators(
            array(
                'ViewHelper',                
                'Label',
            	'Errors',                
                array('HtmlTag',
                    array('tag' => 'div', 'class' => 'formfield')
                )
            )
        );
        
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description');
        $description->setDecorators(
            array(
                'ViewHelper',                
                'Label',
            	'Errors',                
                array('HtmlTag',
                    array('tag' => 'div', 'class' => 'formfield')
                )
            )
        );
        
        $this->addElements(array($degree, $specialties, $payment, $description));

        $this->setLegend('Doctor Details');

        $this->setDecorators(
            array(
                'FormElements', 
                array('Fieldset', array('class' => 'sub floatleft'))                       
            )
        );
        
    }
    

}