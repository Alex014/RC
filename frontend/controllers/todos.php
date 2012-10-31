<?php

/**
 * Actions with tasks
 *
 * @author user
 */
class todosController extends base_controller {
  
  /**
   * Return task list
   * @param type $params
   * @return type 
   */
  public function get($params) {
    $todos = new todos();
    $user_id = USER::get_pk();
    
    $date = unix_to_date($_POST['date'], 'standart');
    $uncompleted = (bool)(int)$_POST['uncompleted'];

    if($uncompleted) {
      $todos = $todos->get($user_id, $date, false);
    }
    else {
      $todos = $todos->get($user_id, $date);
    }
    
    $todos = array_map(function($item) {
      $item['date'] = date_convert('standart', USER::get_field('lang'), $item['date']);
      return $item;
    }, $todos);
    
    print json_encode($todos);
    die();
  }
  
  /**
   * Return single task
   * @param type $params 
   */
  public function get_item($params) {
    $todos = new todos();
    $id = $params[0];
    $user_id = USER::get_pk();
    print json_encode($todos->get_item($user_id, $id));
    die();
  }
  
  /**
   * Create new task
   * @param type $params 
   */
  public function add($params) {
    $todos = new todos();
    $user_id = USER::get_pk();
    $input = input::instance();
    $name = $input->data['name'];
    $date = date_convert(USER::get_field('lang'), 'standart', $input->data['date']);
    //print "add($user_id, $name, $date)";
    $todos->add($user_id, $name, $date);
    print '1';
    die();
  }
  
  /**
   * Edit task
   * @param type $params 
   */
  public function edit($params) {
    $todos = new todos();
    $id = $params[0];
    $user_id = USER::get_pk();
    $input = input::instance();
    var_dump($input->data);
    $date = date_convert(USER::get_field('lang'), 'standart', $input->data['date']);
    
    if(isset($input->data['name']))
      $todos->edit($user_id, $id, $date, $input->data['name']);
    else
      $todos->edit($user_id, $id, $date);
    
    print $id;
    die();
  }
  
  /**
   * Delete task
   * @param type $params 
   */
  public function del($params) {
    $todos = new todos();
    $id = $params[0];
    $user_id = USER::get_pk();
    $todos->del($user_id, $id);
    print '1';
    die();
  }
  
  /**
   * Priority up
   * @param type $params 
   */
  public function up($params) {
    $todos = new todos();
    $id = $params[0];
    $user_id = USER::get_pk();
    $todos->up($user_id, $id);
    print '1';
    die();
  }
  
  /**
   * Priority down
   * @param type $params 
   */
  public function down($params) {
    $todos = new todos();
    $id = $params[0];
    $user_id = USER::get_pk();
    $todos->down($user_id, $id);
    print '1';
    die();
  }
  
  /**
   * Complete task
   * @param type $params 
   */
  public function done($params) {
    $todos = new todos();
    $id = $params[0];
    $user_id = USER::get_pk();
    $todos->done($user_id, $id);
    print '1';
    die();
  }
  
  /**
   * Uncomplete task
   * @param type $params 
   */
  public function undone($params) {
    $todos = new todos();
    $id = $params[0];
    $user_id = USER::get_pk();
    $todos->undone($user_id, $id);
    print '1';
    die();
  }
}