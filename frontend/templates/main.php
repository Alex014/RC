<!DOCTYPE html>
<html>
  <head>
    <title>Todos</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    
<style>
  .center-block {
    width: 400px; 
    padding: 18px;
    margin: 0px auto 0px auto; 
  }
  fieldset {
    width: 220px; 
    padding: 18px;
    margin: 200px auto 0px auto; 
    border: 1px solid silver;
    border-radius: 8px;
  }
  fieldset legend {
    padding: 0px 12px 0px 12px;
    border: 1px solid silver;
    width: auto;
    border-radius: 8px;
  }
  fieldset form {
    margin: 0px; 
  }
</style>
    
  </head>
  <body>
      <div class="navbar">
        <div class="navbar-inner">
          <a class="brand" href="/">Todos</a>
          <ul class="nav">
            <li <?if($method=='register') echo ' class="active"'?>>
              <a href="/Register">Register</a>
            </li>
            <li <?if($method=='login') echo ' class="active"'?>>
              <a href="/">Login</a>
            </li>
          </ul>
        </div>
      </div>
      <?=$template_content?>
  </body>
</html>