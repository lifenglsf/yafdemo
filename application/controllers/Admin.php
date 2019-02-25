<?php
declare(strict_types=1);

class AdminController extends BaseController
{
    protected $pdo;

    public function init()
    {
        parent::init();
        if (Yaf_Session::getInstance()->has('username')) {
            $this->getView()->assign('username', Yaf_Session::getInstance()->offsetGet('username'));
        } else {
            return $this->redirect('/Login');
        }
        $request = $this->getRequest();
        $module = strtolower($request->getModuleName());
        $controller = strtolower($request->getControllerName());
        $action = strtolower($request->getActionName());
        if (!$this->_checkPerm($module, $controller, $action) && $action != 'logout') {
            $this->getView()->setScriptPath(APPLICATION_PATH . "/application/views");
            $this->getView()->display('common/403.phtml');
            Yaf_Dispatcher::getInstance()->autoRender(false);
            exit;
        }
    }

    public function logoutAction()
    {
        $session = Yaf_Session::getInstance();
        $session->del('username');
        $session->del('userid');
        echo '登出成功';
        $this->redirect('index');
    }

    private function _checkPerm($module, $controller, $action)
    {
        $username = Yaf_Session::getInstance()->offsetGet('username');
        if ($username == 'admin') {
            return true;
        } else {
            $userid = Yaf_Session::getInstance()->offsetGet('userid');
            $sql = 'SELECT * FROM userrole ur LEFT JOIN role r ON ur.rid=r.id WHERE ur.uid=:uid AND lower(r.modulename)=:modulename AND lower(r.controllername)=:controllername AND lower(r.actioname)=:actioname';
            $stmt = $this->pdo->prepare($sql) or die(print_r($this->pdo->errorInfo()));
            $stmt->bindValue(':uid', $userid);
            $stmt->bindValue(':modulename', $module);
            $stmt->bindValue(':controllername', $controller);
            $stmt->bindValue(':actioname', $action);
            $stmt->execute();
            $row = $stmt->fetchAll();
            if (empty($row)) {
                return false;
            }
            return true;
        }

    }

    public function indexAction()
    {
    }

    public function roleAction()
    {
        $sth = $this->pdo->prepare('SELECT * FROM `role`');
        $sth->execute();
        $list = $sth->fetchAll();
        $this->getView()->assign('list', $list);
        return;
    }

    public function addRoleAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $sql = 'INSERT INTO role(modulename,controllername,actioname) VALUES(:modulename,:controllername,:actioname)';
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':modulename', $post['modulename']);
            $sth->bindValue(':controllername', $post['controllername']);
            $sth->bindValue(':actioname', $post['actioname']);
            $count = $sth->execute();
            Yaf_Dispatcher::getInstance()->autoRender(false);
            $info['error'] = 0;
            $info['msg'] = '添加成功';
            if ($count === false) {
                $info['error'] = 1;
                $info['msg'] = '添加失败，请检查输入数据';
            }
            $this->getView()->assign('info', $info);
            $this->getView()->display('admin/info.html');
            return;
        } else {
            return;
        }
    }

    public function editRoleAction()
    {
        $request = $this->getRequest();
        //print_r($request -> )
        $id = $request->getParam('id');
        if (empty($id) || !ctype_digit($id)) {
            echo "abcccc";
            die('error');
        }
        if ($request->isGet()) {
            $sql = "SELECT * FROM role WHERE id=:id";
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':id', $id);
            $sth->execute();
            $row = $sth->fetch();
            $this->getView()->assign('row', $row);
            return;
        }
        $post = $request->getPost();
        $sql = "UPDATE role SET controllername=:controllername,modulename=:modulename,actioname=:actioname WHERE id=:id";
        $sth = $this->pdo->prepare($sql);
        $sth->bindValue(':modulename', $post['modulename']);
        $sth->bindValue(':controllername', $post['controllername']);
        $sth->bindValue(':actioname', $post['actioname']);
        $sth->bindValue(':id', $id);
        $count = $sth->execute();
        $info['error'] = 0;
        $info['msg'] = '编辑成功';
        Yaf_Dispatcher::getInstance()->autoRender(false);
        if ($count === false) {
            $info['error'] = 0;
            $info['msg'] = '编辑失败，请检查输入的数据';
        }
        $this->getView()->assign('info', $info);
        $this->getView()->display('admin/info.html');
        return;
    }

    public function userAction()
    {
        try {
            $sql = "SELECT * FROM user";
            $sth = $this->pdo->prepare($sql);
            //var_dump($this->pdo);
            //exit;
            $sth->execute();
            $list = $sth->fetchAll();
            $this->getView()->assign('list', $list);
            return;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    public function addUserAction()
    {
        $request = $this->getRequest();
        if ($request->isGet()) {
            return;
        }
        $post = $request->getPost();
        $username = $post['username'];
        $password = $post['password'];
        $password = md5($password);
        $sql = 'INSERT INTO user(username,password) VALUES(:username,:password)';
        $sth = $this->pdo->prepare($sql);
        $sth->bindValue(':username', $username);
        $sth->bindValue(':password', $password);
        $count = $sth->execute();
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $info['error'] = 0;
        $info['msg'] = '新增成功';
        if ($count === false) {
            /* echo '<script>alert("添加失败，请检查用户名");history.back();</script>';
             exit();*/
            $info['error'] = 1;
            $info['msg'] = '添加失败，请检查用户名';

        }
        $this->getView()->assign('info', $info);
        $this->getView()->display('admin/info.html');
        return;
        // echo '<script>alert("新增成功");window.location="/admin/addUser"</script>';
    }

    public function editUserAction()
    {

    }

    public function setRoleAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        if (empty($id) || (!ctype_digit($id))) {
            die('error');
        }

        if ($request->isGet()) {
            $sql = "SELECT rid FROM userrole WHERE uid=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $userrole = [];
            $userroles = $stmt->fetchAll();
            foreach ($userroles as $k => $v) {
                $userrole[] = $v['rid'];
            }
            $this->getView()->assign('userrole', $userrole);
            $sql = "SELECT * FROM user WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $user = $stmt->fetch();
            if (empty($user)) {
                die('error');
            }
            $this->getView()->assign('user', $user);
            $sql = "SELECT * FROM role";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $roleList = $stmt->fetchAll();
            $this->getView()->assign('roleList', $roleList);
            return;
        }
        $post = $request->getPost();
        $delsql = 'DELETE FROM userrole WHERE uid=:id';
        $stmt = $this->pdo->prepare($delsql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $isql = 'INSERT INTO userrole(uid,rid)VALUES';
        foreach ($post['ischeck'] as $k => $v) {
            $isql .= '(' . $id . ',' . $v . '),';
        }
        $isql = rtrim($isql, ',');
        $rows = $this->pdo->exec($isql) or die(print_r($this->pdo->errorInfo(), true));
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $info['error'] = 0;
        $info['msg'] = '权限设置成功';
        if ($rows === false) {
            $info['error'] = 1;
            $info['msg'] = '权限设置失败';
        }
        $this->getView()->assign('info', $info);
        $this->getView()->display('admin/info.html');
        return;

    }

    public function categoryAction()
    {
        $sql = 'SELECT * from category';
        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        $list = $sth->fetchAll();
        $this->getView()->assign('list', $list);
        return;
    }

    public function addCategoryAction()

    {
        $request = $this->getRequest();
        if ($request->isGet()) {
            return;
        }
        $post = $request->getPost();
        $sql = 'INSERT INTO category(name,ismulti)values(:name,:ismulti)';
        $sth = $this->pdo->prepare($sql);
        $sth->bindValue(':name', $post['name']);
        $sth->bindValue(':ismulti', $post['ismulti']);
        $count = $sth->execute();
        $info['error'] = 0;
        $info['msg'] = '新增成功';
        Yaf_Dispatcher::getInstance()->autoRender(false);
        if ($count === false) {
            $info['error'] = 1;
            $info['msg'] = '新增失败，请检查分类名称';
        }
        $this->getView()->assign('info', $info);
        $this->getView()->display('admin/info.html');
        return;
    }

    public function editCategoryAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        if (empty($id) || (!ctype_digit($id))) {
            die('error');

        }
        if ($request->isGet()) {
            $sql = "SELECT * FROM category WHERE id=:id";
            $sth = $this->pdo->prepare($sql);
            $sth->bindValue(':id', $id);
            $sth->execute();
            $row = $sth->fetch();
            $this->getView()->assign('row', $row);
            return;
        }
        $post = $request->getPost();
        $sql = 'UPDATE category SET name=:name,ismulti=:ismulti WHERE id=:id';
        $sth = $this->pdo->prepare($sql);
        $sth->bindValue(':name', $post['name']);
        $sth->bindValue(':ismulti', $post['ismulti']);
        $sth->bindValue(':id', $id);
        $count = $sth->execute();
        $info['error'] = 0;
        $info['msg'] = '编辑成功';
        Yaf_Dispatcher::getInstance()->autoRender(false);
        if ($count === false) {
            $info['error'] = 1;
            $info['msg'] = '编辑失败，请检查分类名称';
        }
        $this->getView()->assign('info', $info);
        $this->getView()->display('admin/info.html');
        return;
    }

    public function articleAction()
    {
        $sql = "SELECT a.*,cc.name FROM article a LEFT JOIN category cc ON a.cid = cc.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll();
        $this->getView()->assign('list', $list);
        return;
    }

    public function editArticleAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        if (empty($id) || (!ctype_digit($id))) {
            die('error');
        }
        if ($request->isGet()) {
            $sql = "SELECT * FROM article WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $row = $stmt->fetch();
            if (empty($row)) {
                die('文章id错误');
            }

            $sql = "SELECT * FROM category";
            $stmt = $this->pod->prepare($sql);
            $stmt->execute();
            $list = $stmt->fetchAll();
            $this->getView()->assign('row', $row);
            $this->getView()->assign('categoryList', $list);
            return;
        }
        $post = $request->getPost();
        $sql = 'UPDATE article SET title=:title,cid=:cid,content=:content WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':title', $post['title']);
        $stmt->bindValue(':cid', $post['cid']);
        $stmt->bindValue(':content', $post['content']);
        $stmt->bindValue(':id', $id);
        $rows = $stmt->execute();
        $info['error'] = 0;
        $info['msg'] = '新增成功';
        Yaf_Dispatcher::getInstance()->autoRender(false);
        if ($rows === false) {
            $info['error'] = 1;
            $info['msg'] = '新增失败，请检查输入的内容';
        }
        $this->getView()->assign('info', $info);
        $this->getView()->display('admin/info.html');
        return;

    }

    public function addArticleAction()
    {
        $request = $this->getRequest();
        if ($request->isGet()) {
            $sql = "SELECT * FROM category";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $list = $stmt->fetchAll();
            return $this->getView()->assign('categoryList', $list);
        }
        $post = $request->getPost();
        $sql = 'INSERT into article(title,cid,content) VALUES(:title,:cid,:content)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':title', $post['title']);
        $stmt->bindValue(':cid', $post['cid']);
        $stmt->bindValue(':content', $post['content']);
        $rows = $stmt->execute();
        $info['error'] = 0;
        $info['msg'] = '新增成功';
        Yaf_Dispatcher::getInstance()->autoRender(false);
        if ($rows === false) {
            $info['error'] = 1;
            $info['msg'] = '新增失败，请检查输入的内容';
        }
        $this->getView()->assign('info', $info);
        $this->getView()->display('admin/info.html');
        return;
    }

    public function delArticleAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        if (empty($id) || (!ctype_digit($id))) {
            die('error');
        }
        $post = $request->getPost();
        $sql = 'DELETE FROM article WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $rows = $stmt->execute();
        $info['error'] = 0;
        $info['msg'] = '删除成功';
        Yaf_Dispatcher::getInstance()->autoRender(false);
        if ($rows === false) {
            $info['error'] = 1;
            $info['msg'] = '删除失败';
        }
        $this->getView()->assign('info', $info);
        $this->getView()->display('admin/info.html');
        return;
    }
}
