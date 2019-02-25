<?php
declare(strict_types=1);

class Bootstrap extends Yaf_Bootstrap_Abstract
{
    public function _initSession(Yaf_Dispatcher $dispatcher)
    {
        Yaf_Session::getInstance()->start();
        header("Cache-control:private");
        header('content-type:text/html;charset=utf-8');
    }

    public static function error_handler($errno, $errstr, $errfile, $errline)
    {
        if (error_reporting() === 0) {
            return;
        }
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }


    public function _initConfig()
    {
        error_reporting(E_ALL);
        $config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $config);
    }

    public function _initDefaultName(Yaf_Dispatcher $dispatcher)
    {
        $dispatcher->setDefaultModule('Index')->setDefaultController('Index')->setDefaultAction('index');
    }

    public function _initDb(Yaf_Dispatcher $dispatcher)
    {
        $namespace = $dispatcher->getApplication()->getConfig()->get('application.library.local_namespace');
        $namespace = explode(',', $namespace);
        \Yaf_Loader::getInstance()->registerLocalNamespace($namespace);

    }

    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
        $router = $dispatcher->getRouter();
        //$router->addConfig(Yaf_Registry::get("config")->routes);

        //$route = new Yaf_Route_Rewrite(
        //	'exp/:ident',
        //	array(
        //		'controller' =>'index',
        //		'action' => 'index'
        //	)
        //);
        //$router -> addRoute('exp',$route);
        $route = new Yaf_Route_Rewrite('/admin/:action/:id', array('controller' => 'admin', 'action' => ':action'));
        $router->addRoute('name', $route);
    }

    public function _initLibrary()
    {

    }


    public function _initModels()
    {

    }

    public function _initPlugin()
    {

    }

    public function _initErrorHandler(Yaf_Dispatcher $dispatcher)
    {
        $dispatcher->setErrorHandler(array(get_class($this), 'error_handler'));
    }


}
