<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /*
     * Zend doesn't support namespaces by default, which stinks
     * This will allow it though
     */
    protected function _initAutoloader()
    {
    	$loader = function($className) {
    		$className = str_replace('\\', '_', $className);
    		Zend_Loader_Autoloader::autoload($className);
    	};
    
    	$autoloader = Zend_Loader_Autoloader::getInstance();
    	$autoloader->pushAutoloader($loader, 'Application\\');
    }
    
    /*
     * Load application.ini values needed into registry for easy access
     */
    protected function _initRegistry()
    {
        $options = $this->getOptions();

        Zend_Registry::set('mandrillFrom', $options['mandrill']['from']);
        Zend_Registry::set('mandrillEmail', $options['mandrill']['fromEmail']);
        Zend_Registry::set('mandrillKey', $options['mandrill']['key']);
    }
}

