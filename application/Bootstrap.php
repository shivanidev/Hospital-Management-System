<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{    
    private $_acl = null;
    private $_auth = null;
    private $_view = null;
    private $_baseUrl = null;
    private $_front = null;
    private $_role = null;

    protected function _initView()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $this->_view = $layout->getView();
        $this->_front = Zend_Controller_Front::getInstance();
    }

    protected function _initAcl()
    {
        $this->_auth = Zend_Auth::getInstance();  
        
        if ($this->_auth->hasIdentity()) {
            $roleId = $this->_auth->getStorage()->read()->role_id;
            
            switch ($roleId) {
                case 1:
                    $this->_role = 'admin';
                    break;
                
                case 2:
                    $this->_role = 'doctor';
                    break;
                
                case 3:
                    $this->_role = 'lbuser';
                    break;
                
                case 4:
                    $this->_role = 'chuser';
                    break;

                case 5:
                    $this->_role = 'rsuser';
                    break;
                
                case 6:
                    $this->_role = 'nmuser';
                    break;
                
                case 7:
                    $this->_role = 'patient';
                    break;
                
                default:
                    break;
            }
            
        } else {
            $this->_role = 'guest';
        }       
                
        Zend_Registry::set("role", $this->_role);
        
        $this->_acl = new Application_Model_Acl();
        $this->_front->registerPlugin(new Application_Plugin_Acl($this->_acl));
       	
    }
	   
    protected function _initLoginData()
    {
        if ($this->_auth->hasIdentity()) {           
           $this->_view->loginData = $this->_auth->getStorage()->read();            
        } else {                      
            $this->_view->loginData = null;
        }   
    }

    protected function _initBaseUrl()
    { 
        $request = new Zend_Controller_Request_Http();
        $this->_front->setRequest($request);
        $this->_baseUrl = $this->_front->getBaseUrl();          
    }

    protected function _initDoctype()
    {
        $doctypehelper = new Zend_View_Helper_Doctype();
        $doctypehelper->doctype('HTML5');
    }
        
    protected function _initContenttype()
    {
        $this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');   
    }

    protected function _initNavigation()
    {       
        $config = new Zend_Config_Xml(APPLICATION_PATH.'/configs/navigation.xml','nav');
        $navigation = new Zend_Navigation($config);
      
        $this->_view->navigation($navigation)->setAcl($this->_acl)
                                      ->setRole($this->_role);
    }
         
    protected function _initJquery()
    { 
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();	

        //add the jquery view helper path into your project
        $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");

        //jquery lib includes here (default loads from google CDN)
        $view->jQuery()->enable()//enable jquery ; ->setCdnSsl(true) if need to load from ssl location
             ->addStylesheet($this->_baseUrl.'/js/jquery/themes/redmond/jquery-ui-1.8.14.custom.css')
             ->setLocalPath($this->_baseUrl. '/js/jquery/jquery-1.6.1.min.js')
             ->setUiLocalPath($this->_baseUrl. '/js/jquery/jquery-ui-1.8.14.custom.min.js')
             ->uiEnable();//enable ui    	
    }
    
    protected function _initZendx()
    {
         $this->_view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
    }
    
    protected function _initDb()
    {
        if ($this->hasPluginResource("db")) {
            $db = $this->getPluginResource("db")->getDbAdapter();
            Zend_Db_Table::setDefaultAdapter($db);
            Zend_Registry::set("db", $db);
        } else {
            throw new Exception('Database not configured.');
        }
    }
    
}

