<?php

/**
 * Class front_plugin_example
 *
 * @author Alex14
 */
class front_plugin_example extends base_front_plugin {
  /**
   * Executes before running the controller
   * 
   * @param base_front_controller $front_controller
   * @param type $params plugin params (after @)
   * @param type $all_params all controller params
   */
  public function run(base_front_controller $front_controller, $params, &$all_params) {
    //Return true to run controller
    return true;
    //Return false to
    return false;
  }
}