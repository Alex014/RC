<?php

/**
 * Actions with contacts
 *
 * @author user
 */
class contactsController extends base_controller {
  
  function get($params) {
    $c = new contact();
    print json_encode($c->get((int)$params[0]));
  }
  
  function get_page($params) {
    $page = (int)$_post['page'];
    $per_page = (int)$_post['per_page'];
    $condition = array(
        
    );
    $sort = (int)$_post['sort'];
    $c = new contacts();
    $data = $c->get_page($page, $per_page, $condition, $sort);
  }
  
  function add($params) {
    $data = array(
      
    );
    $c = new contacts();
    $c->add($data);
  }
  
  function edit($params) {
    $data = array(
      
    );
    $c = new contacts();
    $c->edit($data, (int)$params[0]);
  }
  
  function del($params) {
    $c = new contact();
    $c->del((int)$params[0]);
    print '1';
  }
}