<?php
/**
 * Created User: lifeng
 * Create Date: 2019/2/25 17:19
 * Current User:lifeng
 * History User:历史修改者
 * Description:这个文件主要做什么事情
 */

class ErrorController extends Yaf_Controller_Abstract
{
    /**
     * you can also call to Yaf_Request_Abstract::getException to get the
     * un-caught exception.
     */
    public function errorAction($exception)
    {
        assert($exception);
        switch ($exception->getCode()) {
            case YAF_ERR_NOTFOUND_ACTION:
            case YAF_ERR_NOTFOUND_CONTROLLER:
            case YAF_ERR_NOTFOUND_MODULE:
            case YAF_ERR_NOTFOUND_VIEW:
                $this->getView()->display('common/404.phtml');
                break;
            default:
                $this->getView()->display('error/error.phtml');
                break;
        }
        /* $this->getView()->assign("code", $exception->getCode());
         $this->getView()->assign("message", $exception->getMessage());
         $this->getView()->assign("line", $exception->getLine());*/
        /* error occurs */
        /*    switch ($exception->getCode()) {
                case YAF_ERR_NOTFOUND_MODULE:
                case YAF_ERR_NOTFOUND_CONTROLLER:
                case YAF_ERR_NOTFOUND_ACTION:
                case YAF_ERR_NOTFOUND_VIEW:
                    $this->getView()->display('common/404.phtm');
                    break;
                default :
                    $this->getView()->display('common/error.phtm');
                    $message = $exception->getMessage();
                    echo 0, ":", $exception->getMessage();
                    break;
            }*/
    }
}