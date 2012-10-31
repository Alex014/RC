<?php

/**
 * Class slot - improved singleton pattern
 *
 * @author Alex14
 */
class registry {
  private static $instance = NULL;
  private static $data = array();

  /**
   * Instance of class
   * @param string $slot_name
   * @return registry 
   */
  static public function getInstance() {
    if(self::$instance == NULL){
      self::$instance = new registry();
    }
    return self::$instance;
  }
  
  public function __construct() {
    if(self::$instance != NULL) throw new Exception('The instance olready exists');
  }
  
  public function __get($name) {
    return self::$data[$name];
  }
  
  
  public function __set($name, $value) {
    self::$data[$name] = $value;
  }

  
  /**
   * Get data of a slot
   * @return mixed 
   */
  public static function get($path) {
    if( !function_exists('array_get_recursive') ) {
    function array_get_recursive(&$data, $recursive_array_path) {
      if(!is_array($data)) return false;
      $key = $recursive_array_path[0];
      if(count($recursive_array_path) == 1) {
        if(!isset($data[$key]))
          return false;
        else
          return $data[$key];
      }
      else {
        array_shift($recursive_array_path);
        return array_get_recursive($data[$key], $recursive_array_path);
      }
    }}
    
    $path = parser::parse_string($path);
    return array_get_recursive(self::$data, $path);
  }
  
  /**
   * Set data of a slot
   * @param mixed $data 
   */
  public static function set($path, $data) {
    if( !function_exists('array_update_recursive') ) {
    function array_update_recursive(&$data, $recursive_array_path, $value) {
      if(!is_array($data)) $data = array();
      $key = $recursive_array_path[0];
      if(count($recursive_array_path) == 1) {
        $data[$key] = $value;
      }
      else {
        array_shift($recursive_array_path);
        array_update_recursive($data[$key], $recursive_array_path, $value);
      }
    }}
    
    $path = parser::parse_string($path);
        //var_dump(self::$data);
    array_update_recursive(self::$data, $path, $data);
        //var_dump(self::$data);
  }
}

?>
