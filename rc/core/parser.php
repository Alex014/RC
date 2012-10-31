<?php

/**
 * Class parser
 *
 * @author Alex14
 */
class parser {
  public static $delimiter = '.';
  public static $sub_delimiter = ',';
  public static $equals_sign = '=';
  public static $parse_substring = false;
  
  public static function parse_string($string) {
    $params = explode(self::$delimiter, $string);  
    $out_params = array();
    for($i = 0; $i < count($params); $i++) {
      $param = explode(self::$equals_sign, $params[$i]);
      if(count($param) > 1) {
        $param_key = trim($param[0]);
        $param_value = trim($param[1]);
        
        if(self::$parse_substring) {
          
          //Parameter value(s)
          $param_value_list = explode(self::$sub_delimiter, $param_value);
          if(count($param_value_list) > 1)
            $param_value = $param_value_list;

          //Parameter key(s)
          $param_key_list = explode(self::$sub_delimiter, $param_key);
          if(count($param_key_list) > 1) {
            foreach($param_key_list as $key)
              $out_params[$key] = $param_value;
          }
          else {
            $out_params[$param_key] = $param_value;
          }
          
        }
        else {
          $out_params[$param_key] = $param_value;
        }
        
      }
      else {
        $out_params[] = $param[0];
      }
    }
    return $out_params;
  }
}

?>
