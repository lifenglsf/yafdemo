<?php
class IndexsController extends Yaf_Controller_Abstract {
   // default action name
   public function indexAction() {  
        $this->getView()->content = "Hello World";
   }
  
   public function testAction(){
	$response = new Yaf_Response_Http();

$response->setBody('HelloWorld');
echo $response;
	}
}
?>
