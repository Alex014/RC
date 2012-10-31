<?php

/**
 * Class CACHE
 *
 * @author Alex14
 */
class CACHE {
  
  private static $module = '';
  private static $adapter = false;
  private static $cache_stack = array();
  public static $enabled = false;
  
  public static function init($module) {
    self::$module = $module;
    $m = get_config('cache.modules.'.$module);
    self::$enabled = $m['enabled'];
    $adapter = $m['adapter'];
    require dirname(__FILE__).'/adapters/'.$adapter.'.php';
    $adapter = 'cacheAdapter'.$adapter;
    self::$adapter =  new $adapter();
    if(!(self::$adapter instanceof cacheAdapterInterface))
      throw new rcException("The class '$adapter' is not an instance of cacheAdapterInterface");
  }


  /**
   * Start caching
   * put this in the begining
   * 
   * @param type $name - The name of the cache file
   * @param type $class
   * @return type 
   */
  public static function start($name, $class) {
    if(self::$adapter === false)
      throw new rcException('The Cache system is not initialized');
    if(!self::$enabled)
      return true;
    
    if($name == 'url')
      $name = $_SERVER['REQUEST_URI'];
    
    $name = md5($name);  
    $time = get_config('cache.classes.'.$class);

    $time_in = ((time() - self::cache_time($name)) < $time);
    array_push(self::$cache_stack, array($name, $time_in));
    ob_start();
    
    return !$time_in;
  }
  
  /**
   * End caching
   * put this to the end
   * 
   * @return bool (true if cached)
   */
  public static function end() {
    if(!self::$enabled)
      return true;
    
    if(count(self::$cache_stack) == 0)
      throw new rcException('CACHE::end() without start()');
            
    $cache_record = array_pop(self::$cache_stack);
    $name = $cache_record[0];
    $time_in = $cache_record[1];

    if(!self::cache_exists($name)) {
      self::cache_save($name, ob_get_contents());
    }
    elseif($time_in) {
      ob_clean();
      print self::cache_load($name);
    }
    else {
      self::cache_save($name, ob_get_contents());
    }
    ob_end_flush();
    
    return true;
  }
  
  /**
   * Check if cache with this name exists
   * @param type $name - The name of the cache file 
   * @return bool 
   */
  public static function cache_exists($name) {
    if(self::$adapter === false)
      throw new rcException('The Cache system is not initialized');
    return self::$adapter->cache_exists($name);
  }
  
  /**
   * Check if cache with this name exists
   * @param type $name - The name of the cache file 
   * @return bool 
   */
  public static function cache_exists_time($name, $time) {
    if(self::$adapter === false)
      throw new rcException('The Cache system is not initialized');
    return self::$adapter->cache_exists($name) && ((time() - self::$adapter->cache_time($name)) < $time);
  }
  
  /**
   * Load cache from file name.tmp
   * @param type $name - The name of the cache file
   * @return type 
   */
  public static function cache_load($name) {
    if(self::$adapter === false)
      throw new rcException('The Cache system is not initialized');
    return unserialize( self::$adapter->cache_load($name) );
  }
  
  /**
   * Save contents to cache file name.tmp
   * @param type $name - The name of the cache file
   * @param type $contents
   * @return bool 
   */
  public static function cache_save($name, $contents) {
    if(self::$adapter === false)
      throw new rcException('The Cache system is not initialized');
    return self::$adapter->cache_save($name, serialize($contents));
  }
  
  /**
   * Make cache (if nesasery)
   * @param string $name - The name of the cache file - The name of the cache file
   * @param function $l_function - The function that returns data (the function gets called only if nesasery)
   * @return mixed Data or cache contents 
   */
  public static function process($name, $l_function) {
    if(CACHE::$enabled && CACHE::cache_exists($name)) {
      return CACHE::cache_load($name);
    }
    else {
      $event_item =  $l_function();
      if(CACHE::$enabled) CACHE::cache_save ($name, $event_item);
      return $event_item;
    }
  }
  
  /**
   * Check cache file modification time
   * @param type $name - The name of the cache file
   * @return unix time 
   */
  public static function cache_time($name) {
    if(self::$adapter === false)
      throw new rcException('The Cache system is not initialized');
    return self::$adapter->cache_time($name);
  }
}