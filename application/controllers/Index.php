<?php
declare(strict_types=1);

class IndexController extends Yaf_Controller_Abstract
{
    // default action name
    public function indexAction()
    {
        $this->getView()->content = "Hello World";
    }
}

?>
