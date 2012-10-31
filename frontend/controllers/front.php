<?php
/**
 * Description of front_controller
 *
 * @author user
 */
class frontController extends base_front_controller {
  
  function run() {
    //Running all controllers
    $this->runControllers();
  }
  
  /**
   * Event before running controller
   * 
   * @param type $controller - Controller name
   * @param type $method - Controller method
   * @param type $params - Controller parameters
   * @param boolean $continue  - Continue executuion of the controller ?
   */
  public function onRunController($controller, $method, &$params, &$continue) {
    //...
  }
}

?>
