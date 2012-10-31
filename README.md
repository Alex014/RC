                               #RC framework

[What is it][:q]
[Main goals][:w]
[Structure][:e]
[Similar frameworks][:r]
[Install][:t]
[License][:y]

[q]:
##What is it
RC is a WEB development framework, which uses MVC pattern
RC is random two letters
It uses PHP 5.3

[w]:
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

[e]:
##Structure
RC is a classic MVC framework with some differences

Directory structure:
[backend] - the application name
 ...
[frontend]
  [config] - config files
    [routes.php] - routing config file
  [controllers]
  [templates]
  [run.php] - main script (initialize setting, run front controller)
[rc] - base framework classes
  [config] - config files
    [regional] - regional config files
    [autoload.php] - autoload directories
    [config.php] - main config
    [db.php] - database config file
    [path.php] - main rc directories
    [regional.php] - this config loads config files from [regional] directory
    [tables.php] - table names
  [core] - main classes
  [lib] - additional directory with classes
  [models]
[index.php] - run framework from this file

[r]:
##Similar frameworks
 - Code Igniter
 - Fuel

[t]:
##Install
  - install [todos.sql]
  - configure [config/db.php] ($db['dev'])
    Thats it!

The framework comes with a todos sample application
This is a multiuser task planning application using AJAX with REST interface

Components:
  *jQuery - http://jquery.com/
  *Backbone - http://backbonejs.org/
  *Bootstrap - http://twitter.github.com/bootstrap/
  *bootstrap-datepicker - http://www.eyecon.ro/bootstrap-datepicker/
  *handlebars - http://handlebarsjs.com/

[y]:
##License  
Fuck the bureaucracy, I AM AN ANARCHIST !
