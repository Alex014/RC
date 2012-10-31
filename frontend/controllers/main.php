<?php

/**
 * User actions (login, reg, etc)
 *
 * @author user
 */
class mainController extends base_controller {
  
  /**
   * If user logged then show tasks list
   * else show login window
   * @param type $params
   * @return type 
   */
  function index($params) {
    if(!USER::is_logged())
      return $this->front->runController ('main.login', array());
    
    VIEW::template('todos', array(
        'current_date' => unix_to_date(),
        'dt_format' => get_config('regional/'.DEFAULT_LOCALE.'.date_format_js')), 'content');
    VIEW::display('todos_main');
  }
  
  /**
   * Show "404" page
   * @param type $params 
   */
  function error($params) {
    header("Status: 404 Not Found");
    VIEW::display('error404', array('page' => $_GET['url']));
  }
  
  /**
   * Login and regirect to the tasks list
   * @param type $params 
   */
  function login($params) {
      $tparams = array();
      if(isset($_POST['email'])) {
          if(USER::login($_POST['email'], $_POST['pass']))
              redirect::go ('/'.USER::get_field('email'));
          else 
              $tparams['err'] = 'Wrong email or password';
      }
      VIEW::template('login', $tparams, 'content');
      VIEW::display('main', array('method' => 'login'));
  }
  
  /**
   * logout
   * @param type $params 
   */
  function logout($params) {
      USER::logout();
      redirect::go ('/');
  }
  
  /**
   * register
   * @param type $params 
   */
  function register($params) {
      $tparams = array();
      if(isset($_POST['email'])) {
          $data = array('email' => $_POST['email'], 'pass' => md5($_POST['pass']));
          if($user_id = USER::register($data)) {
              USER::force_login($user_id);
              redirect::go ('/'.USER::get_field('email'));
          }
          else {
              $tparams['err'] = 'Internal error';
          }
      }
      VIEW::template('register', $tparams, 'content');
      VIEW::display('main', array('method' => 'register'));
  }
  
  /**
   * Check the user existance
   * @param type $params 
   */
  function exists($params) {
      $exists = USER::exists($_POST['email']);
      if($exists)
          print '1';
      else 
          print '0';
      die();
  }
}