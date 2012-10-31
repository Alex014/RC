<?php
require_once dirname(__FILE__).'/core/rc.php';

error_reporting(E_ALL);
ini_set("display_errors", true);

class rc_loader {
  /**
   * Load RC framework
   * @param type $params
   * 
   * $params['url_type']
   * Available values: 
   * get - index.php?url_path
   * full - http://url_path ($_GET[$params['get_url_index']] = url_path)
   * none - http://site.net/page.php?param=value
   * 
   * $params['get_url_index']
   * If url_type = full then
   * the url path wil be stored in $_GET[$params['get_url_index']]
   * 
   * $params['path']
   * array(regexp => application, ...)
   * 
   * application - rc application name (frontend, backend, admin, etc)
   * 
   * application can be string or array: array('application' => 'backend', 'base_url' => 'admin/')
   * 
   * regexp - regular expression, that matches URL
   * 
   */
  public static function load($params) {
    if(!isset ($params['url_type']))
      throw new Exception('The "url_type" in $params is not set, it must be set to "get" or "full"');
    if(!in_array($params['url_type'], array("get" , "full", "none")))
      throw new Exception('Unknown "url_type" value, it must be set to "get" , "full" or "none"');
    if(($params['url_type'] == 'full') && !isset ($params['get_url_index']))
      throw new Exception('The "get_url_index" in $params is not set');
    if(!isset ($params['path']) || !is_array($params['path']))
      throw new Exception('The "path" in $params is not set or not an array, it must be set to "get" or "full"');

    switch ($params['url_type']) {
      case 'get':
        foreach($_GET as $key => $value) {
          $url = $key; break;
        }
      break;
      case 'full':
        if(isset($_GET[$params['get_url_index']]))
          $url = $_GET[$params['get_url_index']];
        else
          $url = '';
      break;
      case 'none':
        $url = $_SERVER['REQUEST_URI'];
      break;
    }
    
    
    foreach($params['path'] as $regexp => $application) {
      if(is_array($application)) {
        $base_url = $application['base_url'];
        $application = $application['application'];
      }
      //Decorating regexp path
      $match = array("/", "-", "<:num>", "<:string>", "<:string1>", "<:string2>", "<:text>");
      $replace = array("\/", "\-", "([\d]+)", "([\w]+)", "([\w])", "([\w]{2})", "([\w\!@#\$%\^&\*\(\)-\+\=~\-\|\[\]\.]+)");
      $regexp = str_replace($match, $replace, $regexp);
      //Checking for needed URL and running application
      if(preg_match("/$regexp/i", $url) > 0) {
        //print "[$regexp] - [$url] - [$application]";
        $p = new rc($application);
        //Final url
        if(isset($base_url))
          $url = str_replace($base_url, '', $url);
        $url = rtrim($url, "/");
        $url = ltrim($url, "/");
        rc::$url = $url;
        $p->run();
        break;
      }
      
    }
    
  }
  
}