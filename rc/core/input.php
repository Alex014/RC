<?php

/**
 * Class for input data supports
 * GET POST PUT DELETE and other methods
 *
 * @author Alex14
 */
class input {
  private static $instance;
  /**
   * HTTP method (GET POST PUT DELETE)
   * @var type 
   */
  public $method;
  /**
   * HTTP data
   * @var array 
   */
  public $data = array();
  
  public function instance() {
    if(self::$instance == NULL){
      self::$instance = new input();
    }
    return self::$instance;
  }
  
  public function __construct() {
    if(self::$instance != NULL) throw new Exception('The instance olready exists');
    //Parsing HTTP params
    $this->method = $_SERVER['REQUEST_METHOD'];
    $putdata = file_get_contents('php://input');
    $this->data = array();
    parse_str($putdata, $this->data);
    $_GLOBALS['_'.$this->method] = $this->data;
  }
}

?>
