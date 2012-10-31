<?php

if(isset($default_locale)) {
  DEFINE('DEFAULT_LOCALE', $default_locale);
  require_once rc::$core_path . "regional.php";
}

?>
