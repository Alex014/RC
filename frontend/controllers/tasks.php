<?php

/**
 * Actions with tasks
 *
 * @author user
 */
class tasksController extends base_controller {
  
  /**
   * Return task list
   * @param type $params
   * @return type 
   */
  public function get($params) {
    $tasks = new tasks();
    $user_id = USER::get_pk();
    
    $date = unix_to_date($_POST['date'], 'standart');
    $uncompleted = (bool)(int)$_POST['uncompleted'];

    if($uncompleted) {
      $tasks = $tasks->get($user_id, $date, false);
    }
    else {
      $tasks = $tasks->get($user_id, $date);
    }
    
    $tasks = array_map(function($item) {
      $item['date'] = date_convert('standart', USER::get_field('lang'), $item['date']);
      return $item;
    }, $tasks);
    
    print json_encode($tasks);
    die();
  }
  
  /**
   * Return single task
   * @param type $params 
   */
  public function get_item($params) {
    $tasks = new tasks();
    $id = $params[0];
    $user_id = USER::get_pk();
    print json_encode($tasks->get_item($user_id, $id));
    die();
  }
  
  /**
   * Create new task
   * @param type $params 
   */
  public function add($params) {
    $tasks = new tasks();
    $user_id = USER::get_pk();
    $input = input::instance();
    $name = $input->data['name'];
    $date = date_convert(USER::get_field('lang'), 'standart', $input->data['date']);
    //print "add($user_id, $name, $date)";
    $tasks->add($user_id, $name, $date);
    print '1';
    die();
  }
  
  /**
   * Edit task
   * @param type $params 
   */
  public function edit($params) {
    $tasks = new tasks();
    $id = $params[0];
    $user_id = USER::get_pk();
    $input = input::instance();
    var_dump($input->data);
    $date = date_convert(USER::get_field('lang'), 'standart', $input->data['date']);
    
    if(isset($input->data['name']))
      $tasks->edit($user_id, $id, $date, $input->data['name']);
    else
      $tasks->edit($user_id, $id, $date);
    
    print $id;
    die();
  }
  
  /**
   * Delete task
   * @param type $params 
   */
  public function del($params) {
    $tasks = new tasks();
    $id = $params[0];
    $user_id = USER::get_pk();
    $tasks->del($user_id, $id);
    print '1';
    die();
  }
  
  /**
   * Priority up
   * @param type $params 
   */
  public function up($params) {
    $tasks = new tasks();
    $id = $params[0];
    $uncompleted = $params[1];
    $user_id = USER::get_pk();
    $tasks->up($user_id, $id, (bool)$uncompleted);
    print '1';
    die();
  }
  
  /**
   * Priority down
   * @param type $params 
   */
  public function down($params) {
    $tasks = new tasks();
    $id = $params[0];
    $uncompleted = $params[1];
    $user_id = USER::get_pk();
    $tasks->down($user_id, $id, (bool)$uncompleted);
    print '1';
    die();
  }
  
  /**
   * Complete task
   * @param type $params 
   */
  public function done($params) {
    $tasks = new tasks();
    $id = $params[0];
    $user_id = USER::get_pk();
    $tasks->done($user_id, $id);
    print '1';
    die();
  }
  
  /**
   * Uncomplete task
   * @param type $params 
   */
  public function undone($params) {
    $tasks = new tasks();
    $id = $params[0];
    $user_id = USER::get_pk();
    $tasks->undone($user_id, $id);
    print '1';
    die();
  }
}