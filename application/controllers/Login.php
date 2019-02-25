<?php
declare(strict_types=1);

class LoginController extends BaseController
{
    public function init()
    {
        parent::init();
        //phpinfo();
        //exit;
    }

    public function indexAction()
    {
        $request = new Yaf_Request_Http();
        if ($request->isGet()) {
            return;
        }
        if ($request->isPost()) {
            $post = $request->getPost();
            $username = $post['username'];
            $password = $post['password'];
            if ($username == 'admin' && $password == 'admin') {
                Yaf_Session::getInstance()->offsetSet('username', $username);
                $this->redirect("Admin"); // 跳转到login Actios->forward("login", array("from" => "Index")); // 跳转到login Action
                return false;
            } else {
                $password = md5($password);
                $sql = 'SELECT * FROM user WHERE username=:username AND password=:password';
                $stmt = $this->dbo->prepare($sql);
                $stmt->bindValue(':username', $username);
                $stmt->bindValue(':password', $password);
                $stmt->execute();
                $rows = $stmt->fetch();
                if (empty($rows)) {
                    echo "用户名或密码错误";
                } else {
                    Yaf_Session::getInstance()->offsetSet('username', $username);
                    Yaf_Session::getInstance()->offsetSet('userid', $rows['id']);
                    $this->redirect('Admin');
                    return;
                }
            }
            exit;
        }
    }
}
