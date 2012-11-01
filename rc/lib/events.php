<?php

/**
 * Class events
 *
 * @author Alex14
 */
class events {
  private static $events;
  
  /**
   * Create an event
   * @param type $event_name event name
   * @param Closure $fn function to execute
   */
  public static function on($event_name, Closure $fn) {
    self::$events[$event_name][] = $fn;
  }
  
  /**
   * Disable an existing event
   * @param type $event_name  event name
   */
  public static function remove($event_name) {
    unset(self::$events[$event_name]);
  }


  /**
   * Trigger an event
   * @param type $event_name name of event
   * @param type $params the event function will be called with this params
   * @return type true or false (if no events executed)
   */
  public static function trigger($event_name, $params) {
    if(!is_array($params)) $params = array($params);
      
    if(count(self::$events[$event_name]) == 0)
      return false;
    
    foreach (self::$events[$event_name] as $fn) {
      call_user_func_array($fn, $params);
    }
    
    return TRUE;
  }
}

?>
