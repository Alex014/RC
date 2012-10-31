<?php
/**
 * Base class
 * Rewrite Customize
 *
 * @author Alex14
 */
class rc {
  public static $instance;
  
  /**
   * The application path
   * @var type 
   */
  public static $base_path;
  /**
   * The path, where rc class is located
   * @var type 
   */
  public static $core_path;
  /**
   * Base framework path
   * @var type 
   */
  public static $path;
  /**
   * The path with framework modules
   * @var type 
   */
  public static $modules_path;
  
  /**
   * Current URL
   * @var type 
   */
  public static $url;
  
  public static $config_path;
  public static $component_path;
  public static $controllers_path;
  public static $events_path;
  public static $models_path;
  public static $templates_path;
  public static $cache_path;
  
  public static $base_url = "/";
  
  public static $data;
  public static $templates;
  
  /**
   * Constructor
   * 
   * @param type $application_path (base path)
   *  - The root path to other pathes templates, controllers and config files
   *      (You can change it with set_config_path_core() and  set_config_path_application())
   *    by default "$core_path/.." ($core_path - the path, where rc.php is located)
   */
  public function __construct($application_path = '') {
    self::$core_path = dirname(__FILE__)."/";
    self::$path = self::$core_path."../";
    
    require_once self::$core_path.'errors.php';
    require_once self::$core_path.'config_manager.php';

    if($application_path != '')
      self::$base_path = rtrim($application_path, '/').'/';
    else
      self::$base_path = self::$core_path."../";

    self::$config_path = self::$core_path."../config/";
    
    $path = get_config('path');
    //self::$modules_path = self::$base_path.'../'.rtrim($path['modules'], "/")."/";
    self::$controllers_path = self::$base_path.rtrim($path['controllers'], "/")."/";
    self::$events_path = self::$base_path.rtrim($path['events'], "/")."/";
    self::$models_path = self::$core_path.'../'.rtrim($path['models'], "/")."/";
    self::$templates_path = self::$base_path.rtrim($path['templates'], "/")."/";
    self::$cache_path = self::$base_path.'../'.rtrim($path['cache'], "/")."/";
    if(!isset(self::$data)) self::$data = array();
    if(!isset(self::$templates)) self::$templates = array();
    
    self::$instance = $this;
  }
  
  /**
   * Set config path of rc (rc::$config_path) relative to rc::$core_path directory
   */
  public static function set_config_path_core() {
    self::$config_path = self::$core_path."../config/";
  }
  
  /**
   * Set config path of rc (rc::$config_path) relative to rc::$base_path directory
   */
  public static function set_config_path_application() {
    self::$config_path = self::$base_path."config/";
  }
  
  /**
   * Set config path of rc (rc::$config_path) relative to rc::$modules_path directory
   * @param type $module_name - name of your module
   */
  /*public static function set_config_path_module($module_name) {
    self::$config_path = self::$modules_path.$module_name."/config/";
  }*/
  
  public static function include_module($module_name) {
    require_once self::$base_path.$module_name.'.php';
  }
  
  public function __get($name) {
    return self::$data[$name];
  }
  
  
  public function __set($name, $value) {
    self::$data[$name] = $value;
  }


  public static function get_instance() {
    if(!isset(self::$instance))
            self::$instance = new rc();
    return self::$instance;
  }
  
  /**
   *  The function creates new controller
   *  new nameController()
   * @param string $name -name of the controller
   * @return controller class 
   */
  public static function create_controller($name) {
    $name = $name.'Controller';
    $controller = new $name();
    return $controller;
  }
  
  private static function _load_mce ($pClassName) {
    $eClassName = substr($pClassName, 0, strlen($pClassName) - 8);
    $cClassName = substr($pClassName, 0, strlen($pClassName) - 10);
    $smClassName = ltrim($pClassName, '_');
    //Searching for class
    if((strpos($pClassName, 'Controller') !== false) && file_exists(rc::$controllers_path . $cClassName . ".php"))  {
      //Controllers 
      require_once(rc::$controllers_path . $cClassName . ".php");
    }
    elseif((strpos($pClassName, 'Observer') !== false) && file_exists(rc::$events_path . $eClassName . ".php")) {
      //Events
      require_once(rc::$events_path . $eClassName . ".php");
    }
    elseif(file_exists(rc::$models_path . $pClassName . ".php")) {
      //Models
      require_once(rc::$models_path . $pClassName . ".php");
    }
    elseif(file_exists(rc::$models_path . $smClassName . ".php")) {
      //Static Models
      require_once(rc::$models_path . $smClassName . ".php");
    }
  }
  /*
  private static function _load_modules ($pClassName) {
    $path = get_config('path');
    $eClassName = substr($pClassName, 0, strlen($pClassName) - 8);
    $cClassName = substr($pClassName, 0, strlen($pClassName) - 10);
    $smClassName = ltrim($pClassName, '_');
    //Searching in all modules (in modules config.php file)
    $modules = get_config('modules');
    for($i = 0; $i < count($modules); $i++) {
      
      //Searching for class
      if((strpos($pClassName, 'Controller') !== false) && file_exists(rc::$modules_path . $path['controllers'] . '/' . $cClassName . ".php"))  {
        //Controllers 
        require_once(rc::$modules_path . $path['controllers'] . '/' . $cClassName . ".php");
      }
      elseif((strpos($pClassName, 'Observer') !== false) && file_exists(rc::$modules_path . $path['events'] . '/' . $eClassName . ".php")) {
        //Events
        require_once(rc::$modules_path . $path['events'] . '/' . $eClassName . ".php");
      }
      elseif(file_exists(rc::$modules_path . $path['models'] . '/' . $pClassName . ".php")) {
        //Models
        require_once(rc::$modules_path . $path['models'] . '/' . $pClassName . ".php");
      }
      elseif(file_exists(rc::$modules_path . $path['models'] . '/' . $smClassName . ".php")) {
        //Static Models
        require_once(rc::$modules_path . $path['models'] . '/' . $smClassName . ".php");
      }
      elseif(file_exists(rc::$modules_path . 'lib' . '/' . $pClassName . ".php")) {
        //Classes
        require_once(rc::$modules_path . 'lib' . '/' . $pClassName . ".php");
      }
      
    }
    
  }*/
  
  public static function rc_autoload ($pClassName) {
      //Config file
      $autoload = get_config('autoload');
      //Standart paths
      if(isset($autoload['modules'][$pClassName]) && file_exists(rc::$core_path . $autoload['modules'][$pClassName] . ".php"))
        //Predifined modules
        require_once(rc::$core_path . $autoload['modules'][$pClassName] . ".php");
      elseif(file_exists(rc::$core_path . $pClassName . ".php"))
        //Modules (classes)
        require_once(rc::$core_path . $pClassName . ".php");
      else
        self::_load_mce ($pClassName);
        //self::_load_modules ($pClassName);
      //Additional directories
      foreach($autoload['dirs'] as $path) {
        $path = rtrim($path, '/').'/';
        if(isset($autoload['modules'][$pClassName]) && file_exists(rc::$path . $path . $autoload['modules'][$pClassName] . ".php")) {
          //Predifined modules
          require_once(rc::$path . $path . $autoload['modules'][$pClassName] . ".php");
        }
        elseif(file_exists(rc::$path . $path . $pClassName . ".php")) {
          //Modules (classes)
          require_once(rc::$path . $path . $pClassName . ".php");
        }
      }
  }

  /**
   * RUN FRAMEWORK
   * @param string $script_name  - lunch this script after framework is created 
   * (fr_dir/core/run.php by default)
   */
  public static function run($script_name = "run.php") {
    spl_autoload_register(array(__CLASS__, 'rc_autoload'));
    
    if($script_name != '')
      require_once self::$base_path.$script_name;
  }
}

  
  
?>
