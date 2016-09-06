<?php
class Application_Form_WeekdaysSubForm extends Zend_Form_SubForm
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
            	
    	$weekDays = new Zend_Form_Element_Hidden('week_dayes');       
        $weekDays->setDecorators(
            array(
                array(
                    'ViewScript',                
                    array(
                        'viewScript' => 'customviewscripts/_weekdays.phtml',
                        'times' => $this->_options['times']
                    ),              
                )
            )
        );

        $this->addElement($weekDays);

        $this->setLegend('Daily Details');
        
        $this->setDecorators(
                array(
                    'FormElements',
                    array('Fieldset', array('class' => 'sub floatright'))
                )
        );
               
    }
    
    
}