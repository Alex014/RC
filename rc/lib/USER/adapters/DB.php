<?php

/**
 * Class base
 *
 * @author Alex14
 */
class userAdapterDB implements userAdapterInterface {
  private $user_model;
  private $session_alias;
  public $email_field = 'email';
  public $password_field = 'pass';
  public $status_field = '_status';
  
  public function __construct(base_model $user_model, $session_alias = 'USER') {
    $this->user_model = $user_model;
    $this->session_alias = $session_alias;
  }
  
  public function login($email, $password) {
    $password = md5(trim($password));
    $email = trim($email);
    //Select condition row
    $user_row = $this->user_model->select_c_r("*", array($this->email_field => $email, $this->password_field => $password));
    if($user_row) {
      if($user_row[$this->status_field] == 0) {
        $_SESSION[$this->session_alias] = $user_row;
        return true;
      }
      else {
        return 'banned';        
      }
    }
    else {
      return false;
    }
    
  }
  
  public function find($email) {
    $email = trim($email);
    //Select condition row
    return $this->user_model->select_c_r("*", array($this->email_field => $email));
  }
  
  public function exists($email) {
    $email = trim($email);
    //Select condition row
    return (bool)$this->user_model->select_c_v("id", array($this->email_field => $email));
  }
  
  public function force_login($user_id) {
    //Select condition row
    $user_row = $this->user_model->select_pk_r("*", $user_id);
    if($user_row) {
      $_SESSION[$this->session_alias] = $user_row;
      return true;
    }
    else {
      return false;
    }
    
  }
  
  public function logout() {
    unset($_SESSION[$this->session_alias]);
  }
  
  public function change_pass($password) {
    $id = $this->get_pk();
    $password = md5(trim($password));
    return $this->user_model->update_pk(array('pass' => $password), $id);
  }
  
  public function get_pk() {
    return $_SESSION[$this->session_alias]['id'];
  }  
  
  public function get_data() {
    return $_SESSION[$this->session_alias]; 
  } 
  
  public function get_field($field) {
    return $_SESSION[$this->session_alias][$field]; 
  } 
  
  public function set_field($field, $value) {
    $_SESSION[$this->session_alias][$field] = $value; 
  } 
  
  public function is_logged() {
    return isset($_SESSION[$this->session_alias]);
  }
  
  public function register($data) {
    $this->user_model->insert($data);
    return $this->user_model->get_last_id();
  } 
  
}

?>
