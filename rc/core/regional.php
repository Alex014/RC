<?php

//------------------------------------------------------------------------------
//  ******************************************
//  Regional standart date and time functions
//  ******************************************
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
//  Functions using constants
//------------------------------------------------------------------------------

function get_locale_config($locale = '') {
  if($locale != '')
    return get_config("regional/".$locale);
  else
    return get_config("regional/".DEFAULT_LOCALE);
}

function is_date($date, $locale = '') {
  $conf = get_locale_config($locale);
  return preg_match('/'.$conf['date_format_regex'].'/', $date, $matches);
}

function is_time($time, $locale = '') {
  $conf = get_locale_config($locale);
  return preg_match('/'.$conf['time_format_regex'].'/', $time, $matches);
}

function is_datetime($datetime, $locale = '') {
  $conf = get_locale_config($locale);
  return preg_match('/'.$conf['date_time_format_regex'].'/', $datetime, $matches);
}

function date_to_array($date, $locale = '') {
  $conf = get_locale_config($locale);
  $matches = array();
  if(preg_match('/'.$conf['date_format_regex'].'/', $date, $matches)) {
    return array(
        'day' => $matches[$conf['date_format_day_order']], 
        'month' => $matches[$conf['date_format_month_order']],
        'year' => $matches[$conf['date_format_year_order']]);
  }
  else {
    return false;
  }
}

function date_to_unix($date, $locale = '') {
  $dt = date_to_array($date, $locale);
  if($dt !== false) {
    return mktime(0, 0, 0, $dt['day'], $dt['month'], $dt['year']);
  }
  else {
    return false;
  }
}

function datetime_to_array($datetime, $locale = '') {
  $conf = get_locale_config($locale);
  $matches = array();
  if(preg_match('/'.$conf['date_time_format_regex'].'/', $datetime, $matches)) {
    if($matches[6] == '') $matches[6] = '0';
    return array(
        'day' => $matches[$conf['date_format_day_order']], 
        'month' => $matches[$conf['date_format_month_order']],
        'year' => $matches[$conf['date_format_year_order']], 
        'hour' => $matches[$conf['time_format_hour_order'] + 3], 
        'minute' => $matches[$conf['time_format_minute_order'] + 3],
        'second' => $matches[$conf['time_format_second_order'] + 3]);
  }
  else {
    return false;
  }
}

function datetime_to_unix($datetime, $locale = '') {
  $dt = datetime_to_array($datetime, $locale);
  if($dt !== false) {
    return mktime($dt['hour'], 
            $dt['minute'], 
            $dt['second'], 
            $dt['day'], 
            $dt['month'], 
            $dt['year']);
  }
  else {
    return false;
  }
}

function time_to_array($time, $locale = '') {
  $conf = get_locale_config($locale);
  $matches = array();
  if(preg_match('/'.$conf['time_format_regex'].'/', $time, $matches)) {
    if($matches[$conf['time_format_second_order']] == '') $matches[$conf['time_format_second_order']] = '0';
    return array('hour' => $matches[$conf['time_format_hour_order']], 'minute' => $matches[$conf['time_format_minute_order']], 'second' => $matches[$conf['time_format_second_order']]);
  }
  else {
    return false;
  }
}

////////////////////////////////////////////////////////////////

function array_to_date($date, $locale = '') {
  $conf = get_locale_config($locale);
  return str_replace(
          array(
              '<:year>', 
              '<:month>', 
              '<:day>'), 
          array(
              sprintf("%04d", $date['year']), 
              sprintf("%02d", $date['month']), 
              sprintf("%02d", $date['day']) ), 
          $conf['date_format']);
}

function unix_to_date($unix = '', $locale = '') {
  if($unix == '') $unix = time();
  $dt = getdate($unix);
  if($dt)
    return array_to_date( 
            array('day' => $dt['mday'], 
                'month' => $dt['mon'],
                'year' => $dt['year']) , $locale);
  else
    return false;
}

function array_to_datetime($datetime, $locale = '') {
  $conf = get_locale_config($locale);
  $search = array(
      '<:year>', 
      '<:month>', 
      '<:day>', 
      '<:hour>', 
      '<:minute>');
  $replace = array(
      sprintf("%04d", $datetime['year']), 
      sprintf("%02d", $datetime['month']), 
      sprintf("%02d", $datetime['day']), 
      sprintf("%02d", $datetime['hour']), 
      sprintf("%02d", $datetime['minute']));
  return str_replace($search, $replace, $conf['date_time_format']);
}

function array_to_datetime_full($datetime, $locale = '') {
  $conf = get_locale_config($locale);
  $search = array(
      '<:year>', 
      '<:month>', 
      '<:day>', 
      '<:hour>', 
      '<:minute>', 
      '<:second>');
  $replace = array(
      sprintf("%04d", $datetime['year']), 
      sprintf("%02d", $datetime['month']), 
      sprintf("%02d", $datetime['day']), 
      sprintf("%02d", $datetime['hour']), 
      sprintf("%02d", $datetime['minute']), 
      sprintf("%02d", $datetime['second']));
  return str_replace($search, $replace, $conf['date_time_format_full']);
}

function unix_to_datetime($unix = '', $locale = '') {
  if($unix == '') $unix = time();
  $dt = getdate($unix);
  if($dt)
    return array_to_datetime( 
            array('day' => $dt['mday'], 
                'month' => $dt['mon'],
                'year' => $dt['year'], 
                'hour' => $dt['hours'], 
                'minute' => $dt['minutes']), $locale );
  else
    return false;
}

function unix_to_datetime_full($unix = '', $locale = '') {
  if($unix == '') $unix = time();
  $dt = getdate($unix);
  if($dt)
    return array_to_datetime_full( 
            array('day' => $dt['mday'], 
                'month' => $dt['mon'],
                'year' => $dt['year'], 
                'hour' => $dt['hours'], 
                'minute' => $dt['minutes'],
                'second' => $dt['seconds']), $locale );
  else
    return false;
}

function array_to_time($time, $locale = '') {
  $conf = get_locale_config($locale);
  $search = array(
      '<:hour>', 
      '<:minute>');
  $replace = array(
      sprintf("%02d", $time['hour']), 
      sprintf("%02d", $time['minute']));
  return str_replace($search, $replace, $conf['time_format']);
}

function array_to_time_full($time, $locale = '') {
  $conf = get_locale_config($locale);
  $search = array(
      '<:hour>', 
      '<:minute>', 
      '<:second>');
  $replace = array(
      sprintf("%02d", $time['hour']), 
      sprintf("%02d", $time['minute']), 
      sprintf("%02d", $time['second']));
  return str_replace($search, $replace, $conf['time_format_full']);
}

function unix_to_time($unix = '', $locale = '') {
  if($unix == '') $unix = time();
  $time = getdate($unix);
  if($time)
    return array_to_time( 
            array('hour' => $time['hours'], 
                'minute' => $time['minutes']) , $locale);
  else
    return false;
}

function unix_to_time_full($unix = '') {
  if($unix == '') $unix = time();
  $time = getdate($unix);
  if($time)
    return array_to_time_full( 
            array('hour' => $time['hours'], 
                'minute' => $time['minutes'],
                'second' => $time['seconds']), $locale );
  else
    return false;
}

//------------------------------------------------------------------------------
//  Functions with locale
//------------------------------------------------------------------------------

function date_convert($from_locale, $to_locale, $date) {
  $dt = date_to_array($date, $from_locale);
  return array_to_date($dt, $to_locale);
}

function time_convert($from_locale, $to_locale, $time) {
  $tm = time_to_array($time, $from_locale);
  return array_to_time($tm, $to_locale);
}

function time_convert_full($from_locale, $to_locale, $time) {
  $tm = time_to_array($time, $from_locale);
  return array_to_time_full($tm, $to_locale);
}

function datetime_convert($from_locale, $to_locale, $datetime) {
  $dt = datetime_to_array($datetime, $from_locale);
  return array_to_datetime($dt, $to_locale);
}

function datetime_convert_full($from_locale, $to_locale, $datetime) {
  $dt = datetime_to_array($datetime, $from_locale);
  return array_to_datetime_full($dt, $to_locale);
}


//$dt = date_to_array("01.02.2003");
//var_dump($dt);
//$t = time_to_array("23:23");
//var_dump($t);
//$dt = datetime_to_array("01.02.2003 23:23:56");
//var_dump($dt);
//
//$u = date_to_unix("01.02.2003");
//print "[$u]";
//$u = datetime_to_unix("01.02.2003 23:23");
//print "[$u]";
//$u = datetime_to_unix("01.02.2003 23:23:01");
//print "[$u]";
//
//print "<br/>";
//
//$date = unix_to_date($u);
//print "[$date]";
//$date = unix_to_time($u);
//print "[$date]";
//$date = unix_to_time_full($u);
//print "[$date]";
//$date = unix_to_datetime($u);
//print "[$date]";
//$date = unix_to_datetime_full($u);
//print "[$date]";
//
//print "<br/>";
//
//print " ".date_convert('ru', 'mysql', '01.05.2009');
//print " ".date_convert('en', 'ru', '05/03/2010');
//print " ".datetime_convert_full('mysql', 'lv', '2011-11-28 10:14:58');
?>
