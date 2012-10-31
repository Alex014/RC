<?php

/**
 * Class multi_inherit
 *
 * @author Alex14
 */
class plugged {
  
  private static $plugins;
  
  private static function __callStatic($name, $arguments) {
    $fname = rc::$path.'lib/'.get_called_class().'/plugins/'.$name.'.php';
    $fn = require($fname);
    call_user_func_array($fn, $arguments);
  }
}

?>
