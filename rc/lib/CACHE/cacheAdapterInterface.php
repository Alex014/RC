<?php

/**
 * Class cacheAdapterInterface
 *
 * @author Alex14
 */
interface cacheAdapterInterface {
  
  /**
   * Check if cache with this name exists
   * @param type $name 
   * @return bool 
   */
  public function cache_exists($name);
  
  /**
   * Load cache from file name.tmp
   * @param type $name
   * @return type 
   */
  public function cache_load($name);
  
  /**
   * Save contents to cache file name.tmp
   * @param type $name
   * @param type $contents
   * @return bool 
   */
  public function cache_save($name, $contents);
  
  /**
   * Check cache file modification time
   * @param type $name
   * @return unix time 
   */
  public function cache_time($name);
  
}