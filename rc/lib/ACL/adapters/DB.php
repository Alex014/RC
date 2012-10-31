<?php
class aclAdapterDB implements aclAdapterInterface {
  private $user_model;
  private $access_model;
  private $modules_model;
  private $bans_model;
  
  public function __construct(base_model $user_model, base_model $access_model, 
          base_model $modules_model, base_model $bans_model) {
    $this->user_model = $user_model;
    $this->access_model = $access_model;
    $this->modules_model = $modules_model;
    $this->bans_model = $bans_model;
  }
  
  public function getUsers() {
    return $this->user_model->select_c_a("*", '1');
  }
  
  public function getModules() {
    return $this->modules_model->select_c_a("*", '1');
  }
  
  public function getUserAccess($user_id, $module_id) {
    return $this->access_model-> select_c_a("*", array('user_id' => $user_id, 'module_id' => $module_id));
  }
  
  public function getUserAccessModules($user_id) {
    $tables = get_config('teables');
    $sql = <<<SQL
    SELECT M.id, M.name AS `module`, A.`from`, A.`to`
    FROM $tables[modules] M
    LEFT JOIN $tables[modules_access] A ON (A.module_id = M.id AND A.user_id = $user_id)
SQL;
    return db::getQuery($sql);
  }
  
  public function setUserAccess($user_id, $module_id, $from, $to) {
    return $this->access_model->insert(array('user_id' => $user_id, 'module_id' => $module_id, 'from' => $from, 'to' => $to));
  }
  
  public function unsetUserAccess($access_id) {
    return $this->access_model->delete_pk($access_id);
  }
  
  public function unsetUserAccessAll($user_id, $module_id) {
    return $this->access_model->delete_c(array('user_id' => $user_id, 'module_id' => $module_id));
  }
  
  public function setUserAccessModules($user_id, $modules_access) {
    return $this->access_model->delete_c(array('user_id' => $user_id));
    foreach($modules_access as $module_id => $period) {
      $this->access_model->insert(array('user_id' => $user_id, 'module_id' => $module_id, 'from' => $period['from'], 'to' => $period['to']));
    }
  }
  
  public function getUserBans($user_id) {
    return $this->bans_model->select_c_r("*", array('user_id' => $user_id));
  }
  
  public function banUser($user_id, $from, $to, $message) {
    $this->bans_model->delete_c(array('user_id' => $user_id));
    $this->bans_model->insert(array('user_id' => $user_id, 'from' => $from, 'to' => $to, 'message' => $message));
  }
  
  public function unbanUser($user_id) {
    $this->bans_model->delete_c(array('user_id' => $user_id));
  }
  
  public function isAlowed($user_id, $module_id, $date) {
    return $this->access_model->select_c_r("COUNT(*)", "user_id = $user_id AND module_id = $module_id AND ('$date' BETWEEN `from` AND `to`) ");
  }
  
  public function isBanned($user_id, $date) {
    return $this->bans_model->select_c_r("COUNT(*)", array('user_id' => $user_id));
  }
}