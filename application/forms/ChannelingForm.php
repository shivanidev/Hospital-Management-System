<?php

class Application_Form_ChannelingForm extends Zend_Form {

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
        
       	$this->setMethod('post');
                      
        $date = new Zend_Form_Element_Text('date');
        $date->setLabel($options['label']);   
        $date->setRequired(TRUE);
        $date->addValidator('date');
        $date->class = 'w150';
        $date->setDecorators(
        	array(
	           	'ViewHelper',                
	            'Label',
	            'Errors',
	            array('HtmlTag',
	            	array('tag' => 'div', 'class' => 'formfield')
	            )
            )
        );
                    
        $comments = new Zend_Form_Element_Textarea('comment');
        $comments->setLabel('Comments');
        $comments->setDecorators(
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
                    array('tag' => 'div', 'class' => 'mart10')
                )
            )
        );
                            
        $this->addElements(array($date, $comments, $submit));
        
        $this->setDecorators(
            array(
                'FormElements',
                array(
                    'Fieldset',
                    array(
                        'legend' => $options['legend']
                    )
                ),
                'Form'
            )
        );
                      
    }
    
}
