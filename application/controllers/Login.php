<?php
class LoginController extends Yaf_Controller_Abstract{
	public function indexAction(){
		$request = new Yaf_Request_Http();
		if($request -> isGet()){
		return;
		}
		if($request -> isPost()){
			$post = $request -> getPost();
			$username = $post['username'];
			$password = $post['password'];
			if($username == 'admin' && $password == 'admin'){
				Yaf_Session::getInstance() -> offsetSet('username',$username);
				$this->redirect("Admin"); // 跳转到login Actios->forward("login", array("from" => "Index")); // 跳转到login Action
            			 return FALSE;
			}else{
				echo "用户名或密码错误";
			}
			exit;
		}
	}
}
