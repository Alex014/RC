<!DOCTYPE html>
<html>
  <head>
    <title><?=$title?></title>
    <script src="<?rc::$base_url?>js/jquery.js"></script>
    
    <script src="<?rc::$base_url?>js/json2.js"></script>
    <script src="<?rc::$base_url?>js/underscore-min.js"></script>
    <script src="<?rc::$base_url?>js/backbone-min.js"></script>
    
    <link href="<?rc::$base_url?>css/bootstrap.min.css" rel="stylesheet">
    <script src="<?rc::$base_url?>js/bootstrap.min.js"></script>
    
    <link href="<?rc::$base_url?>css/datepicker.css" rel="stylesheet">
    <script src="<?rc::$base_url?>js/bootstrap-datepicker.js"></script>
    
    <script src="<?rc::$base_url?>js/handlebars.js"></script>
  </head>

      <div class="navbar">
        <div class="navbar-inner">
          <a class="brand" href="/">TODOS</a>
          <ul class="nav">
            <li class="active">
              <a href="/<?=USER::get_field('email')?>"><?=USER::get_field('email')?></a>
            </li>
            <li>
              <a href="/logout">Logout</a>
            </li>
          </ul>
        </div>
      </div>
  
      <?=$template_content?>
  
  </body>
</html>