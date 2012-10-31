<?php
require_once dirname(__FILE__).'/base_controller.php';

/**
 * Routing and lunching other controllers
 *
 * @author Alex14
 */
class base_front_controller extends base_controller {
  
  public $role;
  public $url;
  public $route;
  
  /**
   * 
   * @param type $role - used in config/route
   * guest by default
   */
  public function __construct() {
    parent::__construct();
    $this->front = $this;
    $rc = rc::get_instance();
    $rc->frontController = $this;
    
    spl_autoload_register(array(__CLASS__, 'front_autoload'));
  }
  
  public static function front_autoload ($pClassName) {
    if(file_exists(rc::$base_path . "front/" . $pClassName . ".php"))
            require_once(rc::$base_path . "front/" . $pClassName . ".php");
  }

  private function matchRoute($url, $regexp) {
    //route event processes route regexp
    $params = array('url' => $url, 'regexp' => $regexp);
    $this->on_route($params);
    $url = $params['url'];
    $regexp = $params['regexp'];
    
    $matches = array();
    if(preg_match("/^$regexp$/i", $url, $matches)) {
      //print "/^$regexp$/i, $url:1";
      //
      $this->url = $url;
      $this->route = $regexp;
      unset($matches[0]);
      return $matches;
    }
    else {
      //print "/^$regexp$/i, $url:0";
      return false;
    }
  }
  
  public function onRunController($conroller, $method, &$params, &$continue) {
    //...
  }
  
  public function on_default_controller(&$params) {
    //...
  }
  
  public function on_not_found_controller(&$params) {
    //...
  }
  
  public function on_denied_controller(&$params) {
    //...
  }
  
  public function runController($controller_route_string, $params = '', $lunch_event = true) {
    if($params == '') $params = array();
    
    //Parsing
    parser::$delimiter = '.';
    parser::$sub_delimiter = ',';
    parser::$equals_sign = '=';
    parser::$parse_substring = true;
    $route_params = parser::parse_string($controller_route_string); 

    //Parameters
    $controller_name = $route_params[0];
    if(isset($route_params[1]))
      $controller_method = $route_params[1];
    else
      $controller_method = 'index';
    
    unset($route_params[0]);
    unset($route_params[1]);

    $params = array_merge($params , $route_params);
    
    //On run controller event
    $continue = true;
    if($lunch_event)
      $this->onRunController($controller_name, $controller_method, $params, $continue);
    if(!$continue) return false; 

    //Plugins
    foreach ($params as $param_name => $param_values) {
      if(strpos($param_name, '@') === 0) {
        $p_name = substr($param_name, 1);
        /**********************************/
        //Front controller plugins
        $front_plugin_name = "front_plugin_".$p_name;
        $front_plugin = new $front_plugin_name();
        $res = $front_plugin->run($this, $param_values, $params, $controller_name, $controller_method);
        if(!$res)
          return false;
        /**********************************/
        //call_user_method("param_".$p_name, $this, $param_values);
      }
    }
    
    //Run controller
    //print "<br/>$controller_route_string: $controller_name, $controller_method<br/>";
    //var_dump($params);
    $this->execController($controller_name, $controller_method, $params);
    
  }
  
  /**
   * Execute controllers method with params
   * @param type $controller_name - controller
   * @param type $controller_method - controllers method
   * @param type $params  - method's params
   */
  public function execController($controller_name, $controller_method, $params) {
      $controller = rc::create_controller($controller_name);
      $controller->front = $this;
      return $controller->$controller_method($params);
  }
  
  public function runDefaultController($params = '') {
    $routes = get_config_application('routes');
    if(!isset($routes['@default']))
      Throw new routingException ("No default route ('@default')");
    //Event
    $this->on_default_controller($params);
    //
    return $this->runController($routes['@default'], $params);
  }
  
  public function runNotFoundController($params = '') {
    $routes = get_config_application('routes');
    if(!isset($routes['@error']))
      Throw new routingException ("No error route ('@error')");
    //Event
    $this->on_not_found_controller($params);
    //
    header("Status: 404 Not Found");
    return $this->runController($routes['@error'], $params, false);
  }
  
  public function runDeniedController($params = '', $die = true) {
    $routes = get_config_application('routes');
    if(!isset($routes['@denied']))
      Throw new routingException ("No denied route ('@denied')");
    //Event
    $this->on_denied_controller($params);
    //
    header("Status: 403 Denied");
    $this->runController($routes['@denied'], $params, false);
    if($die) die();
  }
  
  
    /**
     * Recursivly parse routing string
     * @param string or array $controller
     * @param array $matches - the route mathching params
     * @return type 
     */
    private function run_controller_parse($controller, $matches) {
        //Many controllers on one route (subarray)
        if(is_array($controller)) {
          foreach($controller as $key => $c) {
            //Route subarray key is string ( $route[regexp][subarray_key] )
            if(is_string($key)) {
              //HTTP method [?method]
              if(strpos($key, '?') === 0) {
                $input = input::instance();
                $method = substr($key, 1);
                if(strtoupper($method) == strtoupper($input->method)) {
                  $this->run_controller_parse($c, $matches);
                }
              }
              //POST params [param=value]
              else {
                
                //Parse subarray key and run controller if key params are equal to POST params
                $str_post = parser::parse_string($key);
                $not_equal = false;
                foreach($str_post as $pkey => $pvalue) {
                  if(strtolower($pvalue) == 'null') {
                    if(isset($_POST[$pkey])) {
                      $not_equal = true;
                      break;
                    }
                  }
                  else {
                    if(!isset($_POST[$pkey])) {
                      $not_equal = true;
                      break;
                    }
                    elseif(($pvalue != '*') && ($_POST[$pkey] != $pvalue)) {
                      $not_equal = true;
                      break;
                    }
                  }
                }
                if(!$not_equal) {
                  $this->run_controller_parse($c, $matches);
                }
                
              }

            }
            else {
              //run controller if there are no POST params
              //print "$url, $url_regexp: $c<br/>";
              $this->run_controller_parse($c, $matches);
            }
          }
        }
        else {
          //print "$url, $url_regexp: $controller<br/>";
          return $this->runController($controller, $matches);
        }
    }
  
    
  protected function runControllers() {
    $routes = get_config_application('routes');
    $url = $_GET['url'];
    //Lanching default controller
    if($url == '') {
      $this->runDefaultController();
      return true;
    }
    //Lanching controller
    foreach ($routes as $url_regexp => $controller) {
      //Regexp matches as params
      $matches = $this->matchRoute($url, $url_regexp);
      
      if($matches !== false) {
        //var_dump($controller); print "<br/>";
        $this->run_controller_parse($controller, $matches);
        return true;
      }
    }
    //If route not found - lanching error controller
    $this->runNotFoundController();
  }

  public function on_route(&$params) {
    $params['url'] = rtrim($params['url'], "/");
    $params['url'] = ltrim($params['url'], "/");
    $match = array("/", "-", "<:num>", "<:string>", "<:string1>", "<:string2>", "<:text>");
    $replace = array("\/", "\-", "([\d]+)", "([\w]+)", "([\w])", "([\w]{2})", "([\w\!@#\$%\^&\*\(\)-\+\=~\-\|\[\]\.]+)");
    $params['regexp'] = str_replace($match, $replace, $params['regexp']);
    //print_r($params);
  }
}

/***************************************************/
/***************************************************/
/***************************************************/
/***************************************************/
/***************************************************/
/***************************************************/

/**
 * Lunch controller method
 * 
 * @param type $controller controller name
 * @param type $method  method name
 * @param type $params  params
 */
function controller_method($controller, $method, $params) {
    $rc = rc::get_instance();
    return $rc->frontController->execController($controller, $method, $params);
}

?>
