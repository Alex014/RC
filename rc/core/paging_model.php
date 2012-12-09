<?php
/**
 * Description of base_model
 *
 * @author user
 */
class paging_model extends base_model {
  public $pk = '';
  public $table = '';
  
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
    $count_sql = $this->get_count_sql($condition);
    $this->total = db::getValue($count_sql); 
    $this->page_count = floor(($this->total - 1) / $per_page) + 1;

    $this->select_sql .= $this->condition_condition($condition);
    $start = $per_page*($page - 1);
    if($sort != '')
      $sort = " ORDER BY $sort";
    $limit = db::sqlLimit($start, $per_page);
    $select_sql = $this->get_select_sql($condition, $sort, $limit);
    return db::getQuery($select_sql);
  }
  
  /**
   * Return the SQL for displaying single page
   * @param type $condition  same as get_page() $condition param
   * @param type $order - SQL order by part
   * @param type $limit  - SQL limit part
   */
  protected function get_select_sql($condition, $order, $limit) {
    //Overwrite
  }
  
  /**
   * Return SQL for counting the total ammount of records
   * @param type $condition   same as get_page() $condition param
   */
  protected function get_count_sql($condition) {
    //Overwrite
  }
  
  function get($pk) {
    $this->select_pk_r('*', $pk);
  }

  function add($data) {
    $this->insert($data);
  }

  function edit($data, $pk) {
    $this->update_pk($data, $pk);
  }

  function del($pk) {
    $this->delete_pk($pk);
  }
}