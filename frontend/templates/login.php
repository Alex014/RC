<fieldset>
    <legend>Login</legend>
    <form method="post">
        <label for="email">Email</label>
        <input name="email" id="email" type="text"/>
        <label for="pass">Password</label>
        <input name="pass" id="pass" type="password"/>
        
        <input class="btn pull-right" type="submit" value="Login" name="login"/>
    </form>
</fieldset>
<?if(isset($err)):?>
  <div class="center-block">
    <div  class="alert alert-error">
      <?=$err?>
    </div>
  </div>
<?endif;?>