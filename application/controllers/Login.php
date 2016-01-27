<?php
class LoginController extends Yaf_Controller_Abstract{
	public function init(){
		$dsn = 'mysql:host=127.0.0.1;dbname=yafdemo;charset=utf8';
                  $username = 'root';
                  $password = 'root';
                  $dbo = new PDO($dsn,$username,$password);
                  $dbo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                  $dbo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                  $dbo->exec('SET NAMES UTF8');
  
                  $this -> dbo = $dbo;
	}
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
				$password = md5($password);
				$sql = 'SELECT * FROM user WHERE username=:username AND password=:password';
				$stmt = $this -> dbo -> prepare($sql);
				$stmt ->bindValue(':username',$username);
				$stmt -> bindValue(':password',$password);
				$stmt -> execute();
				$rows = $stmt -> fetch();
				if(empty($rows)){
					echo "用户名或密码错误";
				}else{
					Yaf_Session::getInstance() -> offsetSet('username',$username);
					Yaf_Session::getInstance() -> offsetSet('userid',$rows['id']);
					$this ->redirect('Admin');
					return;
				}
			}
			exit;
		}
	}
}
