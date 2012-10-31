<?php

/**
 * Class USER
 *
 * @author Alex14
 */
class VIEW extends plugged {
  
  public static $templates_path;
  public static $template_vars;
  private static $subtemplates;
  
  /**
   *
   * @param string $template - template to show
   * @param array $params - template params
   * @param mixed $return
   * @return 
   * $return === true: return template as string
   * $return === false: 
   *  the template will be assigned to template list with the old name
   * $return is string (template name)
   *  the template will be assigned to template list with the new name ($return)
   */
  public static function template($template, $params, $return = false) {
    //Changing variable names to avoid collisions
    $opimfgf0123456789_template = $template;
    $opimfgf0123456789_params = $params;
    $opimfgf0123456789_return = $return;
    unset($template);
    unset($params);
    unset($return);
    //Template variables is stored in VIEW::$template_vars
    $rc = rc::get_instance();
    if(!isset(self::$templates_path))
      self::$templates_path = rc::$templates_path;
    //Current template variables
    if(!is_array(self::$template_vars)) self::$template_vars = array();
    if(!is_array(self::$template_vars[$opimfgf0123456789_template])) 
            self::$template_vars[$opimfgf0123456789_template] = array();
    self::$template_vars[$opimfgf0123456789_template] = 
            array_merge(self::$template_vars[$opimfgf0123456789_template], $opimfgf0123456789_params);
    //"Main" variables for all templates
    extract(self::$template_vars['wp948jtjw3tjsggj5894g'], EXTR_OVERWRITE);

    //Behavior
    if($opimfgf0123456789_return === true) {
      //Templates
      foreach(self::$subtemplates as $opimfgf0123456789_tmpl => $opimfgf0123456789_name) {
        //Setting current template variables
        extract(self::$template_vars[$opimfgf0123456789_tmpl], EXTR_SKIP);
        
        //Collecting all subtemplates into variables with prefix template_
        ob_start();
        include self::$templates_path."$opimfgf0123456789_tmpl.php";
        $opimfgf0123456789_tname = "template_".$opimfgf0123456789_name;
        $$opimfgf0123456789_tname = ob_get_clean();
        
        //Destroying current template variables
        foreach(self::$template_vars[$opimfgf0123456789_tmpl] as $name => $value)
          //Checking if it is not a global template variable
          if(!isset(self::$template_vars['wp948jtjw3tjsggj5894g'][$name]))
            unset($$name);      
      }
      ob_start();
      
      extract(self::$template_vars[$opimfgf0123456789_template], EXTR_SKIP);
      
      include self::$templates_path."$opimfgf0123456789_template.php";
      return ob_get_clean();
    }
    elseif($opimfgf0123456789_return === false) {
      if(!is_array(self::$subtemplates)) self::$subtemplates = array();
      $x = self::$subtemplates;
      $x[$opimfgf0123456789_template] = $opimfgf0123456789_template;
      self::$subtemplates = $x;
    }
    elseif(is_string($opimfgf0123456789_return)) {
      if(!is_array(self::$subtemplates)) self::$subtemplates = array();
      $x = self::$subtemplates;
      $x[$opimfgf0123456789_template] = $opimfgf0123456789_return;
      self::$subtemplates = $x;
    }
    else 
      return false;
    
  }
  
  /**
   * Assign template variables to all templates
   * @param type $vars 
   */
  public static function set_template_vars($vars) {
    $rc = rc::get_instance();
    if(!is_array(self::$template_vars)) self::$template_vars = array();
    if(!is_array(self::$template_vars['wp948jtjw3tjsggj5894g'])) 
            self::$template_vars['wp948jtjw3tjsggj5894g'] = array();
    self::$template_vars['wp948jtjw3tjsggj5894g'] = array_merge(self::$template_vars['wp948jtjw3tjsggj5894g'], $vars);
  }
  
  /**
   * Display template
   * 
   * @param type $template
   * @param array $params 
   */
  public static function display($template, $params = '') {
    if($params == '') $params = array();
    print VIEW::template($template, $params, true);
  }
  
  /**
   * htmlentities to array
   * @param type $data array
   * @return type 
   */
  public static function htmlentities($data) {
    foreach ($data as $key => $value) {
      $data[$key] = htmlentities($value);
    }
    return $data;
  }
  
  /**
   * strip_tags to array
   * @param type $data
   * @param type $allowable_tags array
   * @return type 
   */
  public static function strip_tags($data, $allowable_tags = '') {
    foreach ($data as $key => $value) {
      $data[$key] = strip_tags($value, $allowable_tags);
    }
    return $data;
  }
}

?>
