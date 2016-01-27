<?php
class Bootstrap extends Yaf_Bootstrap_Abstract{
	public function _initSession(Yaf_Dispatcher $dispatcher){
		Yaf_Session::getInstance() -> start();
		header("Cache-control:private");
		header('content-type:text/html;charset=utf-8');
	}

	public function _initConfig(){
		$config = Yaf_Application::app() -> getConfig();
		Yaf_Registry::set('config',$config);
	}
	
	public function _initDefaultName(Yaf_Dispatcher $dispatcher){
		$dispatcher -> setDefaultModule('Index') -> setDefaultController('Index') -> setDefaultAction('index');
	}

	public function _initDb(){

	}

	public function _initRoute(Yaf_Dispatcher $dispatcher){
		$router = $dispatcher -> getRouter();
		//$router->addConfig(Yaf_Registry::get("config")->routes);

		//$route = new Yaf_Route_Rewrite(
		//	'exp/:ident',
		//	array(
		//		'controller' =>'index',
		//		'action' => 'index'
		//	)
		//);
		//$router -> addRoute('exp',$route);
		$route = new Yaf_Route_Rewrite('/admin/:action/:id',array('controller' => 'admin','action' => ':action'));
		$router -> addRoute('name',$route);
	}
	
	public function _initLibrary(){

	}

	public function _initModels(){

	}

	public function _initPlugin(){

	}
}
