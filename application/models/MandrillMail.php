<?php
namespace Application\Model;

/**
 * Mail class that builds the 'message' part of the json structure
 * 
 * This is separated from the MandrillMailer because it shouldn't actually
 * have any concern about how it is being sent
 * 
 * @author Steve
 *
 */
class MandrillMail
{
    private $from;
    private $fromEmail;
    private $messageStructure;
    
    public function __construct($html, $subject)
    {
        $this->from = \Zend_Registry::get('mandrillFrom');
        $this->fromEmail = \Zend_Registry::get('mandrillEmail');
        
        $data = array(
            'html' => $html,
            'subject' => $subject      
        );
        $this->messageStructure = $this->createMessageStructure($data);
    }
    
    /**
     * Add a new to entry to this mail object
     * @param $email The email of the contact
     * @param $name The name of the contact
     * @param string $type The type of entry to use: to, cc, bcc
     */
    public function addTo($email, $name, $type = 'to')
    {
        $this->messageStructure['to'][] = array(
            'email' => $email,
            'name' => $name,
            'type' => $type      
        );
    }

    /**
     * Add a zip attachment to this mail object
     * @param $filePath The file path of the attachment, it will be deleted
     */
    public function addZipAttachment($filePath)
    {
        if(!isset($this->messageStructure['attachments']))
        {
            $this->messageStructure['attachments'] = array();
        } 
        $fileData = file_get_contents($filePath);
        
        $name = basename($filePath);
        $base64File = base64_encode($fileData);
        $mimeType = 'application/zip';
        
        //delete this from our system
        unlink($filePath);
        
        $this->messageStructure['attachments'][] = array(
            'type' => $mimeType,
            'name' => $name,
            'content' => $base64File
        );
    }
    
    /**
     * 
     * @return Array containing the message structure
     */
    public function getMessage()
    {
        return $this->messageStructure;
    }
    
    /**
     * 
     * @param array $data array containing html, subject, from_email, from_name, to
     * @return An array containing a fresh message structure
     */
    private function createMessageStructure(array $data)
    {
        $message = array();
        
        $message['html'] = $data['html'];
        $message['subject'] = $data['subject'];
        $message['from_email'] = $this->fromEmail;
        $message['from_name'] = $this->from;
        $message['to'] = array();
        
        return $message;
    }
}

