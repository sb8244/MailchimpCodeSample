<?php

use Application\Model\MandrillMail;
use Application\Model\MandrillMailer;
use Application\Form\Upload;

class IndexController extends Zend_Controller_Action
{
    /**
     * Our action which handles all form information
     */
    public function indexAction()
    {
        $form = new Upload();
        $this->view->form = $form;
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getPost();
            if($form->isValid($data))
            {
                $file = $form->file->receive();
                
                if($file !== false)
                {
                    $filePath = $form->file->getFileName();
                    $to = $form->getValue('to');
                    $html = $form->getValue('html');
                    $subject = $form->getValue('subject');
                    $sendResult = $this->send($html, $subject, explode(",", $to), $filePath);
                    
                    $this->view->success = $sendResult;
                }
            }
            else
            {
                //errors happened and we need to show that
                $form->populate($data);
            }
        }
    }

    /**
     * Send a mail with attachment
     * @param $html HTML content of message
     * @param array $to Array of emails to send to
     * @param $filePath The file path of upload
     * @throws Exception
     * @return boolean Success of send mail
     */
    private function send($html, $subject, array $to, $filePath)
    {
        if($filePath != null && $to != null)
        {
            $mail = new MandrillMail($html, $subject);
            foreach($to as $email)
            {
                $mail->addTo($email, $email);
            }
            $mail->addZipAttachment($filePath, 'Index Script');
            $mailer = new MandrillMailer();
            $res = $mailer->send($mail);
            
            if($res !== true)
            {
                throw new Exception("Could not send file: " . $res );
            }
            else
            {
                return true;
            }
        }
        return false;
    }
}

