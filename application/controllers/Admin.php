<?php
class AdminController extends Yaf_Controller_Abstract{
	protected $dbo;
	public function init(){
		phpinfo();
exit;
		$dsn = 'mysql:host=127.0.0.1;dbname=yafdemo';
		$username = 'root';
		$password = 'root';
		$dbo = new PDO($dsn,$username,$password);
		$this -> dbo = $dbo;
	}
	public function indexAction(){
		if(Yaf_Session::getInstance() -> has('username')){
			$this -> getView() -> assign('username',Yaf_Session::getInstance() -> offsetGet('username'));
		}else{
			$this -> redirect('Admin');
		}
		return ;
	}

	public function roleAction(){
		$list = $this -> dbo -> prepare("SELECT * FROM role") ->execute() -> fetchAll();
		$this -> getView() ->assign('list',$list);
		return;	
	}

	public function addRoleAction(){

	}

	public function editRoleAction(){

	}
	
	public function userAction(){

	}

	public function addUserAction(){

	}

	public function editUserAction(){

	}

	public function categoryAction(){

	}

	public function addCategoryAction()

	{

	}
	
	public function editCategoryAction(){

	}

	public function articleAction(){

	}

	public function editArticleAction(){

	}

	public function addArticleAction(){

	}
}
