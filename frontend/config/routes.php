<?php
/**
 * route index is regular expression
 * $routes['route/<:num>/([0-9]+)'] = 'controller.method.x=y'
 * The following params are accepted <:num> <:string> <:text>
 * 
 * Controller method params:
 *    zero index (0,1,2,3,4,5) - Regex mathes
 *    string index - controller string params aftert method name (x=y)
 * 
 * $routes['@default']: if path is empty
 * $routes['@error']: path not found
 * $routes['@denied']: the access to current user is denied
 * 
 * POST:
 * $routes['register']['save=null'] = 'main.show_register'; ($_POST[save] = NULL)
 * $routes['register']['save=save'] = 'main.do_register'; ($_POST[save] = 'save')
 * $routes['test']['xxx=*'] = 'test.test'; ($_POST[xxx] = [any value])
 * 
 * REST:
 * $routes['test']['?GET'] = 'test.get';
 * $routes['test']['?PUT'] = 'test.insert';
 * $routes['test']['?POST'] = 'test.edit';
 * $routes['test']['?DELETE'] = 'test.delete';
 * 
 * 
 * Plugins:
 *  main.index.@example=xxx,yyy
 *  lunches front/front_plugin_example->run()
 *  with parameters array('xxx', 'yyy')
 * 
 * Examples:
 *  $routes['main/admin'] = 
 *    'admin.index.module=admin.@allow=admin.param_x=xxx';
 * 
 *  $routes['orders/edit/<:num>'] = 
 *    'orders.edit.module=orders.@allow=user,admin';
 * 
 */

$routes['@default'] = 'main.tasks';
$routes['@error'] = 'main.error';
$routes['@denied'] = 'main.login';

$routes['register'] = 'main.register';
$routes['logout'] = 'main.logout';

$routes['usercheck'] = 'main.exists';
$routes['gettasks']['?POST'] = 'tasks.get';
$routes['task/<:num>']['?GET'] = 'tasks.get_item';
$routes['task/<:num>']['?PUT'] = 'tasks.edit';
$routes['task']['?POST'] = 'tasks.add';
$routes['task/<:num>']['?DELETE'] = 'tasks.del';
$routes['task/up/<:num>/<:num>'] = 'tasks.up';
$routes['task/down/<:num>/<:num>'] = 'tasks.down';
$routes['task/done/<:num>'] = 'tasks.done';
$routes['task/undone/<:num>'] = 'tasks.undone';
$routes['tasks'] = 'main.tasks';
$routes['contacts'] = 'main.contacts';