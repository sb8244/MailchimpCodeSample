<?php
namespace Application\Form;

class Upload extends \Zend_Form
{

    public function init()
    {
        $this->addElement('textarea', 'html', array(
        		'label' => 'Email Content',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array('NotEmpty'),
                'class' => 'form-control'
        ));
        

        $this->addElement('text', 'subject', array(
        		'label' => 'Subject',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array('NotEmpty'),
        		'class' => 'form-control'
        ));
        
        $this->addElement('text', 'to', array(
        		'label' => 'To (Comma Separated Emails)',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array('NotEmpty'),
                'placeholder' => '1@1.com,2@2.com',
                'class' => 'form-control'
        ));
        
        //Bit more complex validators that's easiest to manually
        //write out
        $file = $this->addElement('file', 'file', array(
                'label' => 'Attachment ZIP',
                'required' => true,  
        ));
        $file = new \Zend_Form_Element_File('file');
        $file->setLabel('Attachment ZIP');
        $file->setRequired(true);
        $file->addValidator('Count', false, 1);
        $file->addValidator('Extension', false, 'zip');
        $file->setAttrib('class', 'form-control');
        $this->addElement($file);
        
        // Add the submit button
        $this->addElement('submit', 'submit', array(
        		'ignore'   => true,
        		'label'    => 'Send it!',
        ));
        
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
         'ignore' => true,
        ));
    }


}

