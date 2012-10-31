<script src="/js/bootstrap.validate.js"></script>
<script src="/js/bootstrap.validate.en.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    
  $('#regform').bt_validate();

  //Custom check function
  $.bt_validate.fn.custom_pass_eq = function() {
    return ($('#pass').val() == $('#pass2').val());
  }
  //Err message on tooltip (if check function returns false)
  $.bt_validate.text.custom_pass_eq = "The passwords are not equal";

  //Ajax check function
  $.bt_validate.fn.usercheck = function(value) {
    //Checking if value has changed
    if((window['usercheck_prev_value'] != undefined) && (usercheck_prev_value == value))
      return '';
    usercheck_prev_value = value;
    //Blocking form and showing progress
    $.bt_validate.block()
    this.show_tooltip('checking', 'blue');

    var self = this;
    //Timeout
    setTimeout(function() {
        //Ajax call
        $.post('/usercheck', {'email': value}, function(res) {
          if(res == '1') {
            //User with same email is found
            self.show_err_tooltip('This email is olready used');
          }
          else {
            //If no user found, showing "green" tooltip and unblocking the form
            self.show_ok_tooltip('OK');
            $.bt_validate.unblock()
          }
        });      
    },250);
    //Return empty string, if you want to skip standart checking
    return '';
  }
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