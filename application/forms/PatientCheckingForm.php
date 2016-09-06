<?php
class Application_Form_PatientCheckingForm extends Zend_Form 
{
    public function init() 
    {
        $note = new Zend_Form_Element_Textarea("note");
        $note->setLabel("Description");
        $note->setDecorators(array(
                'ViewHelper',
                'Label',
                'Errors',
                array('HtmlTag',
                    array('tag' => 'div', 'class' => 'formfield')
                )
            )
        );

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");
        $submit->class = 'button blue';
        
        $this->addElements(array($note, $submit)); 
        
        $this->addDecorators(array(
                'FormElements', 
                array(
                    'Fieldset',
                    array('legend' => 'Checking Note')
                ),
                'Form'
            )
        );
    }
}

