<?php
namespace Application\Model;

/**
 * The actual sender for the mail object
 * 
 * @author Steve
 *
 */
class MandrillMailer
{
    private $key;
    private $url;
    
    public function __construct()
    {
        //keep it outside of the code in the ini!
        //I'm choosing to not pass it as a dependency, because this style
        //will make dependency injection easier (if need be)
        $this->key = \Zend_Registry::get('mandrillKey');
        $this->url = 'https://mandrillapp.com/api/1.0/messages/send.json';
    }

    /**
     * Send a MandrillMail object across using MandrillAPI
     * 
     * Typically, I would not use cURL, in ZF, and would instead use the Zend_HTTP
     * classes. But for the purpose of demo code, I will use cURL.
     * 
     * @param MandrillMail $mail
     * @return string|mixed
     */
    public function send(MandrillMail $mail)
    {
        //create Mandrill mail structure
        $data = array(
            'key' => $this->key,
            'message' => $mail->getMessage()
        );
        
        $jsonData = json_encode($data);
        
        $curl = curl_init();
        
        //Set up cURL with our POSTed JSON data
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        //Ugh, I don't want to have to do this, but I'm running
        //this on Windows and I don't have proper certs setup.
        //This would never be the case on a production machine...
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $result = curl_exec($curl);
        
        //We have to check for the error just incase something bad happens
        //like an ssl cert issue...
        $error = false;
        if($result === false)
        {
            $error = curl_error($curl);
        }
        curl_close($curl);
        
        if($error !== false)
        {
            return $error;
        }
        return true;
    }
}

