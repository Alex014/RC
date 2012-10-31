<?php

/**
 * Description of base_controller
 *
 * @author user
 */
class templated_controller extends base_controller {
  
  public $base_template = '';
  
  public function __construct() {
    //...;
  }
  
  public function __call($method_name, $arguments) {
    if($this->base_template != '')
      VIEW::template($method_name, $arguments[0], $this->base_template);
    else
      VIEW::display($method_name, $arguments[0]);
  }
  
}

?>
