<?php

/**
 * Class cacheAdapterFiles
 *
 * @author Alex14
 */
class cacheAdapterFiles implements cacheAdapterInterface {
  
  /**
   * Check if cache with this name exists
   * @param type $name 
   * @return bool 
   */
  public function cache_exists($name) {
    return file_exists(rc::$cache_path.$name.".tmp");
  }
  
  
  /**
   * Load cache from file name.tmp
   * @param type $name
   * @return type 
   */
  public function cache_load($name) {
    return file_get_contents(rc::$cache_path.$name.".tmp");
  }
  
  /**
   * Save contents to cache file name.tmp
   * @param type $name
   * @param type $contents
   * @return bool 
   */
  public function cache_save($name, $contents) {
    return file_put_contents(rc::$cache_path.$name.".tmp", $contents);
  }
  
  /**
   * Check cache file modification time
   * @param type $name
   * @return unix time 
   */
  public function cache_time($name) {
    return filemtime(rc::$cache_path.$name.".tmp");
  }
  
}