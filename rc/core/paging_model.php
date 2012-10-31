<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of base_model
 *
 * @author user
 */
class paging_model extends base_model {
  public $pk = '';
  public $table = '';
  
  public $select_sql = "";
  public $count_sql = "";
  
  public $page_count;
  public $per_page;
  public $page;
  public $total;
  
  /**
   * Вернуть результат селекта страницы
   * 
   * @param int $page - страница (1,2,3 ...)
   * @param int $per_page - элементов на странице
   * @param type $condition - условие
   * @param type $sort - условие сорировки ( после  ORDER BY )
   * @return type 
   */
  public function get_page($page, $per_page, $condition = '1', $sort = '') {
    $this->page = $page;
    if($per_page < 1) $per_page = 1;
    if($page < 1) $page = 1;
    $this->per_page = $per_page;
    $this->count_sql .= $this->condition_condition($condition);
    $this->total = db::getValue($this->count_sql); 
    $this->page_count = floor(($this->total - 1) / $per_page) + 1;

    $this->select_sql .= $this->condition_condition($condition);
    $start = $per_page*($page - 1);
    if($sort != '')
      $this->select_sql .= " ORDER BY $sort";
    $this->select_sql .= " LIMIT $start, $per_page";
    return db::getQuery($this->select_sql);
  }
  
  
}

?>
