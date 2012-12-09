<?php
/**
 * Simple model with paging functionality
 */
class contacts extends paging_model {
  public $pk = 'id';
  public $table = 'contacts';
  
  protected function get_select_sql($condition, $order, $limit) {
    $condition = $this->condition_condition($condition);
    return "SELECT * FROM $this->table WHERE $condition $order $limit";
  }
  

  protected function get_count_sql($condition) {
    $condition = $this->condition_condition($condition);
    return "SELECT COUNT(*) FROM $this->table WHERE $condition";
  }
}