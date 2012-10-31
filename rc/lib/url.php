<?php

/**
 * Class url
 *
 * @author Alex14
 */
class url {
  
  private static $url_prefix = array();
  public static $lang = 'en';
  
  public static function urlize($value) {
    $value = mb_ereg_replace('[\s\+]', '-', $value);
    return $value;
  }
  
  public static function change_url($lang, $url_items = '') {
    $url = urldecode($_SERVER['REQUEST_URI']);

    if(mb_strlen($url) < 2)
      return '/'.self::$lang;
    
    $lang_items = get_config_application('url.'.$lang);
    $current_lang_items = get_config_application('url.'.self::$lang);
    foreach($current_lang_items as $key => $item) {
      $url = mb_ereg_replace($current_lang_items[$key], $lang_items[$key], $url);
    }
    if(is_array($url_items)) {
        $url = explode('/', $url);
        $url = array_replace($url, $url_items);
        $url = implode('/', $url);
    }
    
    $url = mb_ereg_replace("\/".self::$lang, "/".$lang, $url);
    
    return $url;
  }
  
  /**
   * Create url prefix from items $url_items[0]/$url_items[1]/ ....
   * @param type $url_items 
   */
  public static function set_prefix($url_items) {
    if(!is_array($url_items))
      throw new rcException('');
    self::$url_prefix = $url_items;
  }
  
  /**
   * Clear url prefix
   */
  public static function clear_prefix() {
    self::$url_prefix = array();
  }
  
  /**
   * Create the URL string with prefix
   * @param type $url_items
   * @param type $lang
   * @return type 
   */
  public static function create($url_items, $lang = '') {
    if($lang == '') $lang = self::$lang;
    $lang_items = get_config_application('url.'.$lang);
    
    foreach($url_items as $url_item) {
      if(isset($lang_items[$url_item]))
        $url[] = $lang_items[$url_item];
      else
        $url[] = self::urlize ($url_item);
    }
    
    return '/'.$lang.'/'.implode('/', array_merge(self::$url_prefix, $url));
  }
}

?>
