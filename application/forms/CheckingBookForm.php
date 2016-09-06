<?php

class Application_Form_CheckingBookForm extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $textDecorator = array(
            'ViewHelper',
            'Label',
            'Errors',
            array('HtmlTag',
                array('tag' => 'div', 'class' => 'formfield')
            )
        );


        $date = new Zend_Form_Element_Text("date");
        $date->setLabel("Checking Date");
        $date->setRequired(true);
        $date->addValidator('date');
        $date->setDecorators($textDecorator);
        
        /*
        $name = new Zend_Form_Element_Text("patientId");
        $name->setLabel("Patient Reg/Id");
        $name->setRequired(true);
        $name->setDecorators($textDecorator);
        */
        
        $comments = new Zend_Form_Element_Textarea("comments");
        $comments->setLabel("Comments");
        $comments->setDecorators($textDecorator);

        $this->addDisplayGroup(
                array($date, $comments), 'chbookdetails', array('legend' => '')
        );


        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");
        $submit->class = 'button blue';
        $this->addElement($submit);
    }

}

?>
