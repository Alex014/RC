/**
 * Bootstrap validate V 0.1 Alpha
 * 
 * No license
 * Do what you want with it
 */
$.bt_validate = {}

$.fn.show_tooltip = function(text, color) {
    $(this).tooltip('destroy');
    var rid = 'tlt_' + parseInt(new Date().getTime());
    var marker = '<div id="'+rid+'"></div>';
    $(this).tooltip(
      {title: marker+text, trigger: 'manual', placement: 'right'});
    $(this).tooltip('show');
    $('#'+rid).parent().css({'background-color': color});
    $('#'+rid).parent().prev().css({'border-right-color': color});
}
  
$.fn.show_ok_tooltip = function(text) {
    $(this).show_tooltip(text, 'green')
}

$.fn.show_err_tooltip = function(text) {
    $(this).show_tooltip(text, 'red')
}


$.bt_validate.result = true;
$.bt_validate.blocked = false;

$.bt_validate.block = function() {
  $.bt_validate.blocked = true;
}

$.bt_validate.unblock = function() {
  $.bt_validate.blocked = false;
}

$.fn.bt_validate = function() {
  $(this).children('input[validate],select[validate],textarea[validate]').blur(function() {
    
    var validate_params = $(this).attr('validate').split('|');
    
    var field_result = true;
    
    for(var i in validate_params) {
      var validate_param = validate_params[i].split(',');
      var fn_name = validate_param[0];
      validate_param[0] = $(this).val();
      var res = $.bt_validate.fn[fn_name].apply($(this), validate_param);
      
      if(typeof(res) != 'string') {
          if(!res) {
            var tl_text = $.bt_validate.text[fn_name];
            for(var j = 1; j < validate_param.length; j++) {
              tl_text = tl_text.replace('%'+j, validate_param[j]);
            }
            $(this).show_err_tooltip(tl_text);
            field_result = false;
            $.bt_validate.result = false;
            break;
          }
      }
      else {
        field_result = false;
      }
    
    }
    if(field_result)
      $(this).tooltip('hide');
  });
  
  $(this).submit(function() {
    $.bt_validate.result = true;
    $('#regform').children('input[validate],select[validate],textarea[validate]').trigger('blur');
    
    if($.bt_validate.blocked) return false;
    return $.bt_validate.result;
  });
}