<?php

/**
 * Class redirect
 *
 * @author Alex14
 */
class redirect {
  
  
  /**
   *
   * @param type $path 
   */
  public static function go($path) {
    header("location: ".$path);
  }
  
  /**
   * Get saved path
   * @param type $name 
   */
  public static function pathGet($name) {
    return $_SESSION['path'][$name];
  }
  
  /**
   * Get saved path
   * @param type $name 
   */
  public static function pathExists($name) {
    return isset($_SESSION['path'][$name]);
  }
  
  /**
   * Save path
   * @param type $name 
   */
  public static function pathSave($name) {
    $_SESSION['path'][$name] = $_SERVER['REQUEST_URI'];
  }
  
  /**
   * Redirect to saved (pathSave) path
   * @param type $name 
   */
  public static function pathRedirect($name) {
    header("location: ".$_SESSION['path'][$name]);
  }
}

?>
