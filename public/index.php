<?php
define("APPLICATION_PATH", dirname(dirname(__FILE__)));
$app = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
switch ($app->environ()) {
    case 'dev':
        ERROR_REPORTING(E_ALL);
        ini_set('display_errors', 'On');
        break;
    case 'product':
        ERROR_REPORTING(0);
        ini_set('display_errors', 'Off');
        break;
    default:
        break;
}
$app->bootstrap()->run();//call bootstrap methods defined in Bootstrap.php
// $app  ->run();
