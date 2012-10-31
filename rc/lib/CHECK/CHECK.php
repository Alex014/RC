<?php
/**
 * Class for chacking input data
 *
 * @author Alex14
 */
class CHECK extends plugged {
    private static $data;
    public static $locale;
    
    /**
     *
     * @param type $config_path check config path
     * @param type $locale locale name (en, de, fr - used in reginal settings)
     * @param type $check_all - check all fields or stop at first error
     */
    public function __construct($config_path, $locale, $check_all = false) {
    }
    
    private static function parse_field($field_name, $config_value) {
      if(is_string($config_value)) {
        $fn_name = $config_value;
        $f_params = array();
        $fn_err = false;
      }
      elseif(is_array($config_value)) {
        
        if(!is_array($config_value) || (count($config_value) < 1))
          throw new Exception($field_name.' </br>The config value in config file must be:<br/> <b>array(function_name [string], function_params [string or array]  (optional), return_if_failed [string] (optional) )</b>');

        $fn_name = $config_value[0];
        unset($config_value[0]);

        if(isset($config_value[1]))
          $fn_params = $config_value[1];
        else
          $fn_params = array();

        if(isset($config_value[2]))
          $fn_err = $config_value[2];
        else
          $fn_err = false;

        if(!is_array($fn_params)) {
          $fn_params = explode(',', $fn_params);
        }
      
      }
      else {
        throw new Exception($field_name.' Wrong config value type, it must be string or array');
      }
      
      $f_params[0] = self::$data[$field_name];
      
      for($i = 0; $i < count($fn_params); $i++)
        $f_params[] = $fn_params[$i];
      for($i = 0; $i < count($f_params); $i++) {
        if(strpos($f_params[$i], '@@') === 0) {
          $fname = substr($f_params[$i], 2);
          $f_params[$i] = self::$data[$fname];
        }
      }

      $res = call_user_func_array(array($this, $fn_name), $f_params);

      if($res)
        return true;
      else
        return $fn_err;
    }

    /**
     * Check array of data
     * @param type $config_path - path to config file
     * @param type $locale - locale
     * @param type $data - data to check
     * @param type $check_all - check all data or stop at first error
     * @return type 
     */
    public static function check($config_path, $locale, &$data, $check_all = false) {
      //Loading config
      $check = get_config($config_path);
      if(count($check) == 0)
        throw new rcException(E_STRICT, "Check config ($config_path) not found", __FILE__, __LINE__);
      self::$locale = $locale;
      
      //Checking
      $results = array();
      self::$data = $data;
      foreach($check as $field_name => $rule) {
        $f_name = trim($field_name, '_');
        $res = $this->parse_field($f_name, $rule);
        if($res !== true) {
          if($check_all)
            $results[$field_name] = $res;
          else
            return array($field_name => $res);
        }
      }
      
      if($check_all && (count($results) == 0))
        return true;
      else
        return $results;
    }
  
    public static function not_empty($value) {
      return (isset($value) && ($value != '0') && ($value != ''));
    }

    public static function is_equal($value, $eq_value) {
      return ($value == $eq_value);
    }

    public static function is_array($value) {
      return is_array($value);
    }

    public static function is_float($value) {
      return is_float($value);
    }

    public static function is_int($value) {
      return is_int($value);
    }
    
    public static function is_num($value) {
      return is_numeric($value);
    }

   public static function value_between($value, $min, $max) {
      if(($value >= $min) && ($value <= $max)) {
        return true;
      }
      else
        return false;
    }

    public static function value_less($value, $max) {
      if($value < $max) {
        return true;
      }
      else
        return false;
    }

    public static function value_greater($value, $min) {
      if($value > $min) {
        return true;
      }
      else
        return false;
    }

    public static function length_between($value, $min, $max) {
      if(is_string($value) && (strlen($value) >= $min) && (strlen($value) <= $max))
        return true;
      else
        return false;
    }

    public static function length_less($value, $max) {
      if(is_string($value) && (strlen($value) < $max))
        return true;
      else
        return false;
    }

    public static function length_greater($value, $min) {
      if(is_string($value) && (strlen($value) > $min))
        return true;
      else
        return false;
    }

    public static function is_url($value) {
      return preg_match("/^(http:\/\/)|(https:\/\/)[a-z0-9\/\.-_]+\.[a-z]{2,3}$/i", trim($value));
    }
    
    public static function is_email($value) {
      return preg_match("/^[a-z0-9-_\.]+@[a-z0-9-_\.]+\.[a-z]{2,3}$/i", trim($value));
    }

    public static function is_date($value) {
      return is_date($value, $this->locale);
    }

    public static function is_time($value) {
      return is_date($value, $this->locale);
    }

    public static function is_datetime($value) {
      return is_date($value, $this->locale);
    }
}

?>
