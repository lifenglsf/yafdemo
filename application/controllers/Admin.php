<?php
class AdminController extends Yaf_Controller_Abstract{
	protected $dbo;
	public function init(){
		
		if(Yaf_Session::getInstance() -> has('username')){
			$this -> getView() -> assign('username',Yaf_Session::getInstance() -> offsetGet('username'));
		}else{
			return $this -> redirect('/Login');
		}
		$dsn = 'mysql:host=127.0.0.1;dbname=yafdemo;charset=utf8';
		$username = 'root';
		$password = 'root';
		$dbo = new PDO($dsn,$username,$password);
		$dbo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  
		$dbo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
		$dbo->exec('SET NAMES UTF8');
		
		$this -> dbo = $dbo;
		$request = $this -> getRequest();
		$module = strtolower($request -> getModuleName());
		$controller = strtolower($request -> getControllerName());
		$action = strtolower($request -> getActionName());
		if(!$this -> _checkPerm($module,$controller,$action)&&$action!='logout'){
			$this -> getView() -> setScriptPath(APPLICATION_PATH."/application/views");
			 $this ->getView() ->display('common/403.phtml');
			Yaf_Dispatcher::getInstance() -> autoRender(FALSE);
			exit;	
		}
	}

	public function logoutAction(){
		$session = Yaf_Session::getInstance();
		$session -> del('username');
		$session -> del('userid');
		echo '登出成功';
		$this -> redirect('index');
	}

	private function _checkPerm($module,$controller,$action){
		$username = Yaf_Session::getInstance() ->offsetGet('username');
		if($username == 'admin'){
			return true;
		}else{
			$userid = Yaf_Session::getInstance() -> offsetGet('userid');
			$sql = 'SELECT * FROM userrole ur LEFT JOIN role r ON ur.rid=r.id WHERE ur.uid=:uid AND lower(r.modulename)=:modulename AND lower(r.controllername)=:controllername AND lower(r.actioname)=:actioname';
			$stmt = $this -> dbo -> prepare($sql) or die(print_r($this ->dbo -> errorInfo()));
			$stmt -> bindValue(':uid',$userid);
			$stmt -> bindValue(':modulename',$module);
			$stmt -> bindValue(':controllername',$controller);
			$stmt ->bindValue(':actioname',$action);
			$stmt -> execute();
			$row = $stmt -> fetchAll();
			if(empty($row)){
				return false;
			}
			return true;
		}
		
	}
	public function indexAction(){
	}

	public function roleAction(){
		$sth = $this -> dbo -> prepare('SELECT * FROM `role`');
		$sth -> execute();
		$list = $sth -> fetchAll();
		$this -> getView() ->assign('list',$list);
		return;	
	}

	public function addRoleAction(){
		$request = $this -> getRequest();
		if($request->isPost()){
			$post = $request -> getPost();
			$sql = 'INSERT INTO role(modulename,controllername,actioname) VALUES(:modulename,:controllername,:actioname)';
			$sth = $this -> dbo -> prepare($sql);
			$sth -> bindValue(':modulename',$post['modulename']);
			$sth ->bindValue(':controllername',$post['controllername']);
			$sth ->bindValue(':actioname',$post['actioname']);
			$count = $sth -> execute();
			if($count === false){
				echo '<script>alert("添加失败，请检查输入数据")</script>';exit();
			}
			echo '<script>alert("添加成功");window.location.href="/admin/addRole"</script>';
		}else{
			return;
		}
	}

	public function editRoleAction(){
		$request = $this -> getRequest();
		//print_r($request -> )
		$id = $request -> getParam('id');
		if(empty($id) || !ctype_digit($id)){
			echo "abcccc";
			die('error');
		}
		if($request -> isGet()){
		$sql = "SELECT * FROM role WHERE id=:id";
		$sth = $this -> dbo -> prepare($sql);
		$sth -> bindValue(':id',$id);
		$sth -> execute();
		$row = $sth -> fetch();
		$this -> getView() -> assign('row',$row);
		return;
		}
		$post = $request -> getPost();
		$sql = "UPDATE role SET controllername=:controllername,modulename=:modulename,actioname=:actioname WHERE id=:id";
		$sth = $this -> dbo -> prepare($sql);
		$sth -> bindValue(':modulename',$post['modulename']);
		$sth -> bindValue(':controllername',$post['controllername']);
		$sth -> bindValue(':actioname',$post['actioname']);
		$sth -> bindValue(':id',$id);
		$count = $sth -> execute();
		if($count === false){
		echo "<script>alert('编辑失败，请检查输入的数据');history.back();</script>";exit();
		}

		echo "<script>alert('编辑成功');window.location.href='/admin/role'</script>";
	}
	
	public function userAction(){
		$sql = "SELECT * FROM user";
		$sth = $this -> dbo -> prepare($sql);
		$sth -> execute();
		$list = $sth -> fetchAll();
		$this -> getView() ->assign('list',$list);
		return;
	}

	public function addUserAction(){
		$request = $this -> getRequest();
		if($request -> isGet()){
			return;
		}
		$post = $request -> getPost();
		$username = $post['username'];
		$password = $post['password'];
		$password = md5($password);
		$sql = 'INSERT INTO user(username,password) VALUES(:username,:password)';
		$sth = $this -> dbo -> prepare($sql);
		$sth ->bindValue(':username',$username);
		$sth -> bindValue(':password',$password);
		$count = $sth -> execute();
		if($count === false){
			echo '<script>alert("添加失败，请检查用户名");history.back();</script>';exit();
		}
		echo '<script>alert("新增成功");window.location="/admin/addUser"</script>';
	}

	public function editUserAction(){

	}

	public function setRoleAction(){
		$request = $this -> getRequest();
		$id = $request -> getParam('id');
		if(empty($id) || (!ctype_digit($id))){
			die('error');
		}

		if($request -> isGet()){
			$sql = "SELECT rid FROM userrole WHERE uid=:id";
			$stmt= $this -> dbo -> prepare($sql);
			$stmt -> bindValue(':id',$id);
			$stmt -> execute();
			$userrole = [];
			$userroles = $stmt -> fetchAll();
			foreach($userroles as $k => $v){
				$userrole[] = $v['rid'];
			}
			$this -> getView() -> assign('userrole',$userrole);
			$sql = "SELECT * FROM user WHERE id=:id";
			$stmt = $this ->dbo -> prepare($sql);
			$stmt -> bindValue(':id',$id);
			$stmt -> execute();
			$user = $stmt -> fetch();
			if(empty($user)){
				die('error');
			}
			$this -> getView() -> assign('user',$user);
			$sql = "SELECT * FROM role";
			$stmt  = $this -> dbo -> prepare($sql);
			$stmt -> execute();
			$roleList = $stmt -> fetchAll();
			$this -> getView() -> assign('roleList',$roleList);
			return;
		}
		$post = $request -> getPost();
		$delsql = 'DELETE FROM userrole WHERE uid=:id';
		$stmt = $this -> dbo -> prepare($delsql);
		$stmt -> bindValue(':id',$id);
		$stmt ->execute();
		$isql = 'INSERT INTO userrole(uid,rid)VALUES';
		foreach($post['ischeck'] as $k =>$v){
			$isql.='('.$id.','.$v.'),';
		}
		$isql = rtrim($isql,',');
		$rows = $this -> dbo -> exec($isql) or die(print_r($this ->dbo ->errorInfo(),true));
		if($rows === false){
			echo '<script>alert("权限设置失败");history.back();</script>';exit;
		}
		echo '<script>alert("权限设置成功");window.location.href="/admin/user";</script>';
		
	}

	public function categoryAction(){
		$sql = 'SELECT * from category';
		$sth = $this -> dbo -> prepare($sql);
		$sth -> execute();
		$list = $sth -> fetchAll();
		$this -> getView() -> assign('list',$list);
		return;
	}

	public function addCategoryAction()

	{
		$request = $this -> getRequest();
		if($request -> isGet()){
			return;
		}
		$post = $request -> getPost();
		$sql = 'INSERT INTO category(name,ismulti)values(:name,:ismulti)';
		$sth = $this -> dbo -> prepare($sql);
		$sth -> bindValue(':name',$post['name']);
		$sth -> bindValue(':ismulti',$post['ismulti']);
		$count = $sth -> execute();
		if($count === false){
		echo '<script>alert("新增失败，请检查分类名称");history.back();"</script>';exit();
		}
		echo '<script>alert("新增成功");window.location="/admin/addCategory"</script>';
	}	
	
	public function editCategoryAction(){
		$request = $this -> getRequest();
		$id = $request -> getParam('id');
		if(empty($id) || (!ctype_digit($id))){
			die('error');
		
		}
		if($request -> isGet()){
			$sql = "SELECT * FROM category WHERE id=:id";
			$sth = $this -> dbo -> prepare($sql);
			$sth -> bindValue(':id',$id);
			$sth -> execute();
			$row = $sth -> fetch();
			$this -> getView() -> assign('row',$row);
			return;
		}
		$post = $request -> getPost();
		$sql = 'UPDATE category SET name=:name,ismulti=:ismulti WHERE id=:id';
		$sth = $this -> dbo -> prepare($sql);
		$sth -> bindValue(':name',$post['name']);
		$sth -> bindValue(':ismulti',$post['ismulti']);
		$sth -> bindValue(':id',$id);
		$count = $sth -> execute();
		if($count === false){
		echo '<script>alert("编辑失败，请检查分类名称");history.back();</script>';exit();
		}
		echo '<script>alert("编辑成功");window.location="/admin/category"</script>';
	}

	public function articleAction(){
		$sql = "SELECT a.*,cc.name FROM article a LEFT JOIN category cc ON a.cid = cc.id";
		$stmt = $this -> dbo -> prepare($sql);
		$stmt -> execute();
		$list = $stmt -> fetchAll();
		$this -> getView() -> assign('list',$list);
	}

	public function editArticleAction(){
		$request = $this -> getRequest();
		$id = $request ->getParam('id');
		if(empty($id) || (!ctype_digit($id))){
			die('error');
		}
		if($request -> isGet()){
			$sql = "SELECT * FROM article WHERE id=:id";
			$stmt = $this -> dbo -> prepare($sql);
			$stmt ->bindValue(':id',$id);
			$stmt -> execute();
			$row = $stmt -> fetch();
			if(empty($row)){
				die('文章id错误');
			}
		
			$sql = "SELECT * FROM category";
			$stmt = $this -> dbo -> prepare($sql);
			$stmt -> execute();
			$list = $stmt -> fetchAll();
			$this -> getView() -> assign('row',$row);
			$this -> getView() -> assign('categoryList',$list);
			return;
		}
		$post = $request -> getPost();
		$sql = 'UPDATE article SET title=:title,cid=:cid,content=:content WHERE id=:id';
		$stmt = $this -> dbo -> prepare($sql);
		$stmt -> bindValue(':title',$post['title']);
		$stmt -> bindValue(':cid',$post['cid']);
		$stmt -> bindValue(':content',$post['content']);
		$stmt -> bindValue(':id',$id);
		$rows = $stmt -> execute();
		if($rows === false){
			echo '<script>alert("新增失败，请检查输入的内容");hostory.back();</script>';exit;
		}
		echo '<script>alert("新增成功");window.location.href="/admin/addArticle";</script>';

	}

	public function addArticleAction(){
		$request = $this -> getRequest();
		if($request -> isGet()){
			$sql = "SELECT * FROM category";
			$stmt = $this -> dbo -> prepare($sql);
			$stmt -> execute();
			$list = $stmt -> fetchAll();
			return $this -> getView() -> assign('categoryList',$list);
		}
		$post = $request -> getPost();
		$sql = 'INSERT into article(title,cid,content) VALUES(:title,:cid,:content)';
		$stmt = $this -> dbo -> prepare($sql);
		$stmt -> bindValue(':title',$post['title']);
		$stmt -> bindValue(':cid',$post['cid']);
		$stmt -> bindValue(':content',$post['content']);
		$rows = $stmt -> execute();
		if($rows === false){
			echo '<script>alert("编辑失败，请检查输入的内容");hostory.back();</script>';exit;
		}
		echo '<script>alert("编辑成功");window.location.href="/admin/article";</script>';
	}

	public function delArticleAction(){
		$request = $this -> getRequest();
		$id = $request ->getParam('id');
		if(empty($id) || (!ctype_digit($id))){
			die('error');
		}
		$post = $request -> getPost();
		$sql = 'DELETE FROM article WHERE id=:id';
		$stmt = $this -> dbo -> prepare($sql);
		$stmt -> bindValue(':id',$id);
		$rows = $stmt -> execute();
		if($rows === false){
			echo '<script>alert("删除失败");hostory.back();</script>';exit;
		}
		echo '<script>alert("删除成功");window.location.href="/admin/article";</script>';
	}
}
