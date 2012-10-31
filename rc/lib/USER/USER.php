<?php

/**
 * Class USER
 *
 * @author Alex14
 */
class USER extends adaptable {
  private static $adapter;
  private static $locale;
  private static $locale_db;
  
  public static function init(userAdapterInterface $adapter, $locale = 'en', $locale_db = 'mysql') {
    self::$adapter = $adapter;
    self::$locale = $locale;
    self::$locale_db = $locale_db;
  }
  
  public static function login($username, $pass) {
    return self::$adapter->login($username, $pass);
  }
  
  public static function find($email) {
    return self::$adapter->find($email);
  }
  
  public static function exists($email) {
    return self::$adapter->exists($email);
  }
  
  public static function force_login($user_id) {
    return self::$adapter->login($username, $pass);
  }
  
  public static function logout() {
    return self::$adapter->logout();
  }
  
  public static function change_pass($password) {
    return self::$adapter->change_pass($password);
  }
  
  public static function get_pk() {
    return self::$adapter->get_pk();
  }  
  
  public static function get_data() {
    return self::$adapter->get_data();
  } 
  
  public static function get_field($name) {
    return self::$adapter->get_field($name);
  } 
  
  public static function set_field($field, $value) {
    return self::$adapter->set_field($field, $value);
  } 
  
  public static function is_logged() {
    return self::$adapter->is_logged();
  }
  
  public static function register($data) {
    return self::$adapter->register($data);
  }
}

USER::registerAutoload();