<?php

/**
 * Base class of all controllers
 *
 * @author Alex14
 */
class base_controller {

  /**
   * Front controller
   * @var base_front_controller 
   */
  public $front;
  
  /**
   *
   * @param type $templates_path 
   */
  public function __construct($front) {
    //...
    $this->front = $front;
  }
  
  /*public function display($template, $params) {
    foreach($params as $name => $value)
      $$name = $value;
    foreach(rc::$templates as $template => $contents) {
      $tn = "template_$template";
      $$tn = $contents;
    }
    return require $this->templates_path."$template.php"; 
  }*/
  

}

?>
