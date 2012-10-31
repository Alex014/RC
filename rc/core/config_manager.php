<?php
/**
 * Load config module from file with params
 * 
 * @param type $config_path path to the config file (path/file)
 * @param type $params
 * @return type 
 */
function load_config($config_path, $params = '') {
    $c = ConfigManager::get_instance();
    return $c->load_config($config_path, $params);
}

/**
 * Return any config param
 * the modules are in (protected/config directory)
 * 
 * @param string $config_path module_name.param_name.sub_param
 * @return mixed 
 */
function get_config($config_path) {
    $c = ConfigManager::get_instance();
    return $c->get_config($config_path);
}


/**
 * Load config module from file with params (application path)
 * 
 * @param type $config_path path to the config file (path/file)
 * @param type $params
 * @return type 
 */
function load_config_application($config_path, $params = '') {
    //Loading application config path
    $old_config_path = rc::$config_path;
    rc::set_config_path_application();
    
    $c = ConfigManager::get_instance();
    $result = $c->load_config($config_path, $params);
    //Restoring old config path
    rc::$config_path = $old_config_path;
    
    return $result;
}

/**
 * Return any config param 
 * the modules are in (application_path/config directory)
 * 
 * @param string $config_path module_name.param_name.sub_param
 * @return mixed 
 */
function get_config_application($config_path) {
    //Loading application config path
    $old_config_path = rc::$config_path;
    rc::set_config_path_application();
    
    $c = ConfigManager::get_instance();
    $result = $c->get_config($config_path);
    //Restoring old config path
    rc::$config_path = $old_config_path;
    
    return $result;
}


/**
 * Load config module from file with params (module path)
 * 
 * @param string $module_name name of your module
 * @param type $config_path path to the config file (path/file)
 * @param type $params
 * @return type 
 */
function load_config_module($module_name, $config_path, $params = '') {
    //Loading application config path
    $old_config_path = rc::$config_path;
    rc::set_config_path_module($module_name);
    
    $c = ConfigManager::get_instance();
    $result = $c->load_config($config_path, $params);
    //Restoring old config path
    rc::$config_path = $old_config_path;
    
    return $result;
}

/**
 * Return any config param 
 * the modules are in (your_module/config directory)
 * 
 * @param string $module_name name of your module
 * @param string $config_path module_name.param_name.sub_param
 * @return mixed 
 */
function get_config_module($module_name, $config_path) {
    //Loading application config path
    $old_config_path = rc::$config_path;
    rc::set_config_path_module($module_name);
    
    $c = ConfigManager::get_instance();
    $result = $c->get_config($config_path);
    //Restoring old config path
    rc::$config_path = $old_config_path;
    
    return $result;
}


/**
 * Delete config module from config local storage
 * 
 * @param string $module_path module name
 */
function delete_config($module) {
    $c = ConfigManager::get_instance();
    return $c->delete_config();
}

/**
 * Description of config_manager
 *
 * @author user
 */
class ConfigManager {
  public static $instance;
  public static $config;
  
  public function __construct() {
    ConfigManager::$config = array();
  }
  
  public static function get_instance() {
    if(!isset(ConfigManager::$instance))
            ConfigManager::$instance = new ConfigManager();   
    return ConfigManager::$instance;
  }
  
  /**
   * Extracting module name with params
   * @param type $config_path 
   */
  private function get_module_name($config_path) {
    $path = explode('/', $config_path);
    return $path[count($path) - 1];
  }
  
  /**
   * Extracting module path
   * @param type $config_path 
   */
  private function get_module_path($config_path) {
    $path = explode('/', $config_path);
    unset($path[count($path) - 1]);
    return implode('/', $path);
  }
  
  public function load_config($module_path, $params = '') {
    //Extracting Filename
    $module_name = self::get_module_name($module_path);
    //Loading config file
    if(!isset(ConfigManager::$config[$module_name])) {
      //Loading variables
      foreach (ConfigManager::$config as $module => $content) {
        $$module = $content;
      }
      //Params
      if($params != '')
        foreach($params as $key => $value)
          $$key = $value;
      //Including new module
      include rc::$config_path.$module_path.'.php';
      if(!isset($$module_name))
        ConfigManager::$config[$module_name] = array();
      else
        ConfigManager::$config[$module_name] = $$module_name;
    }
  }
  
  public function delete_config($module_path) {
    //Extracting Filename
    $module_name = self::get_module_name($module_path);
    //Eraising data
    unset(ConfigManager::$config[$module_name]);
  }
  
  public function get_config($config_path = '') {
    if($config_path == '') {
      return ConfigManager::$config;
    }
    else {    
      $module_name = self::get_module_name($config_path);
      $module_path = self::get_module_path($config_path);
      //module name
      $cp = explode('.', $module_name);
      $module_name = $cp[0];
      //return config
      $this->load_config($module_path.'/'.$module_name);
      //searching for result
      $result = ConfigManager::$config[$module_name];

      for($i = 1; $i < count($cp); $i++) {
        $result = $result[$cp[$i]];
      }
      return $result;
    }
  }
  
}

?>
