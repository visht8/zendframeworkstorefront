<?php
/**
 * The application bootstrap used by Zend_Application
 *
 * @category   Bootstrap
 * @package    Bootstrap
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Bootstrap extends Zend_Application_Bootstrap_Base
{
    /**
     * @var Zend_Log
     */
    protected $_logger;

    /**
     * @var Zend_Controller_Front
     */
    public $frontController;

    /**
     * Setup request and response so we can use Firebug for logging
     * also make the dispatcher prefix the default module
     */
    protected function _initFrontControllerSettings()
    {
        $this->bootstrap('frontController');
        $this->frontController->setResponse(new Zend_Controller_Response_Http());
        $this->frontController->setRequest(new Zend_Controller_Request_Http());
    }

    /**
     * Setup the logging
     */
    protected function _initLogging()
    {
        $this->bootstrap('frontController');
        $logger = new Zend_Log();

        $writer = 'production' == $this->getEnvironment() ?
			new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/logs/app.log') :
			new Zend_Log_Writer_Firebug();
        $logger->addWriter($writer);

		if ('production' == $this->getEnvironment()) {
			$filter = new Zend_Log_Filter_Priority(Zend_Log::CRIT);
			$logger->addFilter($filter);
		}

        $this->_logger = $logger;
        Zend_Registry::set('log', $logger);
    }

    /**
     * Setup locale
     */
    protected function _initLocale()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        $locale = new Zend_Locale('en_GB');
        Zend_Registry::set('Zend_Locale', $locale);
    }

    /**
     * Setup the database profiling
     */
    protected function _initDbProfiler()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        if ('production' !== $this->getEnvironment()) {
            $this->bootstrap('db');
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled(true);
            $this->getPluginResource('db')->getDb()->setProfiler($profiler);
        }
    }

    /**
     * Setup the view
     */
    protected function _initView()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->init();

        $this->_view = $viewRenderer->view;

        // set encoding and doctype
        $this->_view->setEncoding('UTF-8');
        $this->_view->doctype('XHTML1_STRICT');

        // set the content type and language
        $this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $this->_view->headMeta()->appendHttpEquiv('Content-Language', 'en-US');

        // set css links and a special import for the accessibility styles
        $this->_view->headStyle()->setStyle('@import "/css/access.css";');
        $this->_view->headLink()->appendStylesheet('/css/reset.css');
        $this->_view->headLink()->appendStylesheet('/css/main.css');
        $this->_view->headLink()->appendStylesheet('/css/form.css');

        // setting the site in the title
        $this->_view->headTitle('Storefront');

        // setting a separator string for segments:
        $this->_view->headTitle()->setSeparator(' - ');

        // init the layouts
        Zend_Layout::startMvc(array('layout' => 'main',
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts'
            )
        );
    }

    public function run()
    {
        $this->frontController->dispatch();
    }
}