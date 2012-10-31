<?php

/**
 * Class ACL
 * Access Control List
 *
 * @author Alex14
 */
class ACL extends adaptable {
  private $adapter;
  private $locale;
  private $locale_db;
  
  public static function init(aclAdapterInterface $adapter, $locale = 'en', $locale_db = 'mysql') {
    $this->adapter = $adapter;
    $this->locale = $locale;
    $this->locale_db = $locale_db;
  }
  
  public static function getUsers() {
    return $this->$adapter->getUsers();
  }
  
  public static function getModules() {
    return $this->$adapter->getModules();
  }
  
  public static function getUserAccess($user_id, $module_id) {
    return $this->$adapter->getUserAccess($user_id, $module_id);
  }
  
  public static function getUserAccessModules($user_id) {
    return $this->$adapter->getUserAccessModules($user_id);
  }
  
  public static function setUserAccess($user_id, $module_id, $from, $to) {
    $from = date_convert($this->locale, $this->locale_db, $from);
    $to = date_convert($this->locale, $this->locale_db, $to);
    return $this->$adapter->setUserAccess($user_id, $module_id, $from, $to);
  }
  
  public static function unsetUserAccess($access_id) {
    return $this->$adapter->unsetUserAccess($access_id);
  }
  
  public static function unsetUserAccessAll($user_id, $module_id) {
    return $this->$adapter->unsetUserAccessAll($user_id, $module_id);
  }
  
  public static function setUserAccessModules($user_id, $modules_access) {
    return $this->$adapter->setUserAccessModules($user_id, $modules_access);
  }
  
  public static function getUserBans($user_id) {
    return $this->$adapter->getUserBans($user_id);
  }
  
  public static function banUser($user_id, $from, $to, $message) {
    $from = date_convert($this->locale, $this->locale_db, $from);
    $to = date_convert($this->locale, $this->locale_db, $to);
    return $this->$adapter->banUser($user_id, $from, $to, $message);
  }
  
  public static function unbanUser($user_id) {
    return $this->$adapter->unbanUser($user_id);
  }
  
  public static function isAlowed($user_id, $module_id) {
    $date = unix_to_date(time(), $this->locale_db);
    return $this->$adapter->isAlowed($user_id, $module_id, $date);
  }
  
  public static function isBanned($user_id) {
    $date = unix_to_date(time(), $this->locale_db);
    return $this->$adapter->isBanned($user_id, $date);
  }
}

?>
