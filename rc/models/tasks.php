<?php

class tasks extends base_model {
  public $pk = 'id';
  public $table = 'tasks';
  
  /**
   * Return all tasks
   * @param type $user_id
   * @param type $date
   * @param type $completed - show only completed
   * @return type 
   */
  public function get($user_id, $date, $completed = '') {
    $params = array('user_id' => $user_id);
    
    if($completed !== '')
      $params['completed'] = (int)$completed;
    return $this->select_c_a("*, ( (`completed` = 0) AND (`date` < '$date') ) AS overdue", $params, '`order`');
  }
  
  /**
   * Return single task
   * @param type $user_id
   * @param type $id
   * @return bool true or false (if task does not belong to user)
   */
  public function get_item($user_id, $id) {
    if(!$this->is_yours($user_id, $id))
            return FALSE;
    return $this->select_c_r('*', array('user_id' => $user_id, 'id' =>$id));
  }
  
  /**
   * Add new task
   * @param type $user_id
   * @param type $name
   * @param type $date 
   */
  public function add($user_id, $name, $date) {
    $data = array(
        'user_id' => $user_id,
        'name' => $name,
        'date' => $date,
        'order' => $this->select_c_v("MAX(`order`)+1", array('user_id' => $user_id))
    );
    $this->insert($data);
  }
  
  /**
   * Edit task
   * @param type $user_id
   * @param type $id
   * @param type $date
   * @param type $name
   * @return bool true or false (if task does not belong to user) 
   */
  public function edit($user_id, $id, $date, $name = '') {
    if(!$this->is_yours($user_id, $id))
            return FALSE;
    $data['date'] = $date;
    if($name != '')
        $data['name'] = $name;

    $this->update_pk($data, $id);
    return TRUE;
  }
  
  /**
   * Delete te task
   * @param type $user_id
   * @param type $id
   * @return bool true or false (if task does not belong to user) 
   */
  public function del($user_id, $id) {
    if(!$this->is_yours($user_id, $id))
            return FALSE;
    $this->delete_pk($id);
    return TRUE;
  }
  
  /**
   * Priority ++
   * @param type $user_id
   * @param type $id
   * @param type $uncompleted move up in uncompleted set of tasks
   * @return bool true or false (if task does not belong to user) 
   */
  public function up($user_id, $id, $uncompleted = false) {
    if(!$this->is_yours($user_id, $id))
            return FALSE;
    //Completed condition
    if($uncompleted)
      $uncompleted = 'completed = 0';
    else
      $uncompleted = '1';
    //This record id and order
    $row = $this->select_pk_r('*', $id);
    $this_id = $row['id'];
    $this_order = $row['order'];
    //Max smaller record and order
    if($row = $this->select_c_r('*', "`order` < $this_order AND $uncompleted", "`order` DESC", '1')) {
      //Change
      $upper_id = $row['id'];
      $upper_order = $row['order'];
      $this->update_pk(array("order" => $upper_order), $this_id);
      $this->update_pk(array("order" => $this_order), $upper_id);
      return TRUE;
    }
    else {
      return false;
    }
  }
  
  /**
   * Priority --
   * @param type $user_id
   * @param type $id
   * @param type $uncompleted move down in uncompleted set of tasks
   * @return bool true or false (if task does not belong to user) 
   */
  public function down($user_id, $id, $uncompleted = false) {
    if(!$this->is_yours($user_id, $id))
            return FALSE;
    //Completed condition
    if($uncompleted)
      $uncompleted = 'completed = 0';
    else
      $uncompleted = '1';
    //This record id and order
    $row = $this->select_pk_r('*', $id);
    $this_id = $row['id'];
    $this_order = $row['order'];
    //Max smaller record and order
    if($row = $this->select_c_r('*', "`order` > $this_order AND $uncompleted", "`order` ASC", '1')) {
      //Change
      $lower_id = $row['id'];
      $lower_order = $row['order'];
      $this->update_pk(array("order" => $lower_order), $this_id);
      $this->update_pk(array("order" => $this_order), $lower_id);
      return TRUE;
    }
    else {
      return false;
    }
  }
  
  /**
   * Complete task
   * @param type $user_id
   * @param type $id
   * @return bool true or false (if task does not belong to user) 
   */
  public function done($user_id, $id) {
    if(!$this->is_yours($user_id, $id))
            return FALSE;
    $this->update_pk(array('completed' => '1'), $id);
    return TRUE;
  }
  
  /**
   * Uncomplete the task
   * @param type $user_id
   * @param type $id
   * @return bool true or false (if task does not belong to user) 
   */
  public function undone($user_id, $id) {
    if(!$this->is_yours($user_id, $id))
            return FALSE;
    $this->update_pk(array('completed' => '0'), $id);
    return TRUE;
  }
  
  /**
   * Does the task belong to user $user_id
   * @param type $user_id
   * @param type $id task ID
   * @return bool true or false (if task does not belong to user) 
   */
  public function is_yours($user_id, $id) {
    return ($this->select_c_v('COUNT(*)', array('user_id' => $user_id, 'id' =>$id)) > 0);
  }
  
}