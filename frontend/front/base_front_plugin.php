<?php

/**
 * Class base_front_plugin
 * Extend this plugin to 
 * front_plugin_[plugin name]
 *
 * @author Alex14
 */
class base_front_plugin {
  /**
   * Executes before running the controller
   * 
   * @param base_front_controller $front_controller
   * @param type $params plugin params (after @)
   * @param type $all_params all controller params
   */
  public function run(base_front_controller $front_controller, $params, &$all_params) {
    //...
  }
}