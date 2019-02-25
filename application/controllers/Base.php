<?php
/**
 * Created User: lifeng
 * Create Date: 2019/2/18 19:49
 * Current User:lifeng
 * History User:历史修改者
 * Description:这个文件主要做什么事情
 */

class BaseController extends Yaf_Controller_Abstract
{
    protected $pdo;


    public function init()
    {
        $this->pdo = Mysql::getInstance()->getPdo();
    }
}