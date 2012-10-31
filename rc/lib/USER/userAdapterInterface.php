<?php
interface userAdapterInterface {
  public function login($username, $pass);
  public function force_login($user_id);
  public function logout();
  public function change_pass($password);
  public function get_pk();
  public function get_data();
  public function get_field($name);
  public function set_field($field, $value);
  public function is_logged();
  public function register($data);
  public function find($email);
  public function exists($email);
}