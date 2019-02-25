<?php
/**
 * Created User: lifeng
 * Create Date: 2019/2/18 17:25
 * Current User:lifeng
 * History User:历史修改者
 * Description:这个文件主要做什么事情
 */

class Mysql
{
    private static $instance;
    private $pdo;

    private function __construct()
    {
        if (!Yaconf::has('db')) {
            throw new Exception('数据库配置为设置');
        }
        $dbConfig = \Yaconf::get('db');
        $dsn = 'mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'] . ';charset=utf8';
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('SET NAMES UTF8');
        $this->pdo = $pdo;
    }

    public function getPdo()
    {
        return $this->pdo;
    }


    protected function __clone()
    {
        // TODO: Implement __clone() method.
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }
        if (!self::$instance instanceof self) {
            throw  new \PDOException('获取数据库实例失败');
        }
        return self::$instance;
    }


}