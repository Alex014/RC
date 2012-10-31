<?php

/**
 * Class multi_inherit
 *
 * @author Alex14
 */
class adaptable {
  
  public static function registerAutoload() {
    $called_class = get_called_class();
    $adapters_path = rc::$path.'lib/'.$called_class.'/adapters/';

    spl_autoload_register(function ($class) use ($adapters_path, $called_class) {
        $prefix = strtolower($called_class).'Adapter';
        $class = str_replace($prefix, '', $class);
        include $adapters_path .  "$class.php";
    });
  }
}

?>
