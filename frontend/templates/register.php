<script src="/js/bootstrap.validate.js"></script>
<script src="/js/bootstrap.validate.en.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    
  $('#regform').bt_validate();

  //Custom check function
  /*$.bt_validate.fn.custom_pass_eq = function(value) {
    return ($('#pass').val() == $('#pass2').val());
  }
  //Err message on tooltip (if check function returns false)
  $.bt_validate.text.custom_pass_eq = "The passwords are not equal";*/
    
  $.bt_validate.method(
    'custom_pass_eq', 
    function(value) {
      return ($('#pass').val() == $('#pass2').val());
    },
    "The passwords are not equal"
  );  

  //Ajax check function
    $.bt_validate.method(
      'usercheck', 
      $.bt_validate.ajax_check({
        url: '/usercheck', 
        type: 'POST',
        return_type: 'text',
        get_data: function() { return {'email': $('#email').val()} }, 
        get_success: function(res) { return (res == '1'); },
        msg_ok: 'This email is free', 
        msg_checking: 'Checking ...', 
        msg_fail: 'This email is olready used'})
    );
});
</script>

<fieldset>
    <legend>Register</legend>
    <form method="post" id="regform">
        <label for="email">Email</label>
        <input name="email" id="email" type="text" validate="required|email|usercheck"/>
        <label for="email">Lang</label>
        <select name="lang" id="lang" type="text">
            <option value="en">En</option>
            <option value="ru">Ru</option>
            <option value="lv">Lv</option>
            <option value="de">De</option>
        </select>
        <label for="pass">Password</label>
        <input name="pass" id="pass" type="password" validate="min,5"/>
        <label for="pass2">Repeat password</label>
        <input name="pass2" id="pass2" type="password" validate="custom_pass_eq"/>
        
        <input class="btn pull-right" type="submit" value="Register" name="register"/>
    </form>
</fieldset>

<div class="message"><?=$err?></div>