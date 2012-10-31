<?php
interface aclAdapterInterface {
  public function getUsers();
  public function getModules();
  public function getUserAccess($user_id, $module_id);
  public function getUserAccessModules($user_id);
  public function setUserAccess($user_id, $module_id, $from, $to);
  public function unsetUserAccess($access_id);
  public function unsetUserAccessAll($user_id, $module_id);
  public function setUserAccessModules($user_id, $modules_access);
  public function getUserBan($user_id);
  public function banUser($user_id, $from, $to, $message);
  public function unbanUser($user_id);
  public function isAlowed($user_id, $module_id, $date);
  public function isBanned($user_id, $date);
}