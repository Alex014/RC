<?php
//01.01.2001
$en['date_format_regex'] = "([\d]{2})\/([\d]{2})\/([\d]{4})";
//23:23
$en['time_format_regex'] = "([\d]{2}):([\d]{2}):{0,1}([\d]{0,2})";
//01.01.2001 23:23
$en['date_time_format_regex'] = "$en[date_format_regex][\s]+$en[time_format_regex]";


/**
 * Day - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$en['date_format_day_order'] = 2;
/**
 * Month - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$en['date_format_month_order'] = 1;
/**
 * Year - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$en['date_format_year_order'] = 3;
/**
 * Hour - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$en['time_format_hour_order'] = 1;
/**
 * Minute - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$en['time_format_minute_order'] = 2;
/**
 * Second - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$en['time_format_second_order'] = 3;


$en['date_format'] = "<:month>/<:day>/<:year>";
$en['time_format'] = "<:hour>:<:minute>";
$en['time_format_full'] = "<:hour>:<:minute>:<:second>";
$en['date_time_format'] = "$en[date_format] $en[time_format]";
$en['date_time_format_full'] = "$en[date_format] $en[time_format_full]";

$en['date_format_php'] = 'm/d/Y';
$en['time_format_php'] = 'H:i';
$en['time_format_full_php'] = 'H:i';
$en['datetime_format_php'] = 'm/d/Y H:i';
$en['datetime_format_full_php'] = 'm/d/Y H:i:s';

$en['date_format_js'] = 'mm/dd/yyyy';
$en['time_format_js'] = 'HH:MM';
$en['time_format_full_js'] = 'HH:MM:ss';
$en['datetime_format_js'] = 'mm/dd/yyyy HH:MM';
$en['datetime_format_full_js'] = 'mm/dd/yyyy HH:MM:ss';

$en['date_format_mysql'] = '%m/%d/%Y';
$en['time_format_mysql'] = '%H:%i';
$en['time_format_full_mysql'] = '%H:%i:%s';
$en['datetime_format_mysql'] = '%m/%d/%Y %H:%i';
$en['datetime_format_full_mysql'] = '%m/%d/%Y %H:%i:%s';
?>
