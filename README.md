#RC framework

##What is it
RC is a WEB development framework, which uses MVC pattern

RC is random two letters

It uses PHP 5.3

##Main goals
  * Config files
    - All config files are in PHP array format
    - One config file can use another config file
    - Any config file can be loaded using parameters
    - get_config('test2.xyz.x') - load xyz.x param from config file test2
  * Reginal settings
    - Check regional date and time format
    - Convert date and time from one reginal format to another
  * Simple SQL library
    - Easy select/update/insert/delete with one function
  * Routing
    - add additional params to controller
    - routing file is a config file, so it can use other config params
    - Front controller can has custom plugins (for example user access plugin)
    - Integrated REST support
  * Other
    - Cache class
    - User class with ACL support

##Structure
RC is a classic MVC framework with some differences

Directory structure:

* [backend] - the application name
  - ...
* [frontend]
  - [config] - config files
      + [routes.php] - routing config file
  - [controllers]
  - [templates]
  - [run.php] - main script (initialize setting, run front controller)
* [rc] - base framework classes
  - [config] - config files
      + [regional] - regional config files
      + [autoload.php] - autoload directories
      + [config.php] - main config
      + [db.php] - database config file
      + [path.php] - main rc directories
      + [regional.php] - this config loads config files from [regional] directory
      + [tables.php] - table names
  - [core] - main classes
  - [lib] - additional directory with classes
  - [models]
* [index.php] - run framework from this file

##Examples

###CACHE
*Initialization and config
    CACHE::init('frontend');
    CACHE::$enabled = true;

    /* rc/config/cache.php */
    $cache = array(
	'modules' => array(
	  'frontend' => array(
	    'enabled' => false,
	    'adapter' => 'Files'
	  )
	),

    'classes' => array(
	  '1min' => 60,
	  '5min' => 300,
	  '10min' => 600,
	  '30min' => 1800,
	  '1h' => 3600,
	  '2h' => 7200,
	  '6h' => 3600*6,
	  '12h' => 3600*12,
	  '24h' => 3600*24
	)
    );
*In templates
    <? if(CACHE::start('cache for 24 hours, '24h')): ?>
	  HTML <?php  echo 'php';  ?>
    <? endif; CACHE::end(); ?>
*In models and controllers
    $fff = function () use ($id, $place_id) {
	$e = new events();
	$p = new places();
	$event_item = $e->get($id, url::$lang);
	$event_item['place'] = $p->get($place_id, url::$lang);
	return $event_item;
    };

    $event_item = CACHE::process("EVENTS-show $place_id $id", '30min', $fff);
    Event manager
    events::on('event', 
	function($param) {
	  echo "The event has been triggered with param '$param'";
	});
    //...
    events::trigger('event', 'xxx');
###USER class
    USER::init(new userAdapterDB(new users()));
    //...
    if($user_id = USER::register($data)) {
      USER::force_login($user_id);
      redirect::go ('/'.USER::get_field('email'));
    }

    if(USER::login($_POST['email'], $_POST['pass']))
      redirect::go ('/'.USER::get_field('email'));

    $user_id = USER::get_pk();

    /*   rc/lib/USER/adapters/DB.php   */

    public function login($email, $password) {
      //...
    }

    public function register($data) {
      $this->user_model->insert($data);
      return $this->user_model->get_last_id();
    } 
###REST
    $routes['todo/<:num>']['?GET'] = 'todos.get_item';
    $routes['todo/<:num>']['?PUT'] = 'todos.edit';
    $routes['todo']['?POST'] = 'todos.add';
    $routes['todo/<:num>']['?DELETE'] = 'todos.del';


##Similar frameworks
 - Code Igniter http://codeigniter.com/
 - Fuel http://fuelphp.com/
 - Laravel http://laravel.com/

##Install
  - install [todos.sql]
  - configure [config/db.php] ($db['dev'])
  - Run

The framework comes with a todos sample application

This is a multiuser task planning application using AJAX with REST interface

Components:
  * jQuery - http://jquery.com/
  * Backbone - http://backbonejs.org/
  * Bootstrap - http://twitter.github.com/bootstrap/
  * bootstrap-datepicker - http://www.eyecon.ro/bootstrap-datepicker/
  * handlebars - http://handlebarsjs.com/

##License  
Fuck the bureaucracy, I AM AN ANARCHIST !
