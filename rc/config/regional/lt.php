<?php
//01.01.2001
$lt['date_format_regex'] = "([\d]{4})-([\d]{2})-([\d]{2})";
//23:23
$lt['time_format_regex'] = "([\d]{2}):([\d]{2}):{0,1}([\d]{0,2})";
//01.01.2001 23:23
$lt['date_time_format_regex'] = "$lt[date_format_regex][\s]+$lt[time_format_regex]";


/**
 * Day - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$lt['date_format_day_order'] = 1;
/**
 * Month - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$lt['date_format_month_order'] = 2;
/**
 * Year - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$lt['date_format_year_order'] = 3;
/**
 * Hour - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$lt['time_format_hour_order'] = 1;
/**
 * Minute - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$lt['time_format_minute_order'] = 2;
/**
 * Second - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$lt['time_format_second_order'] = 3;

$lt['date_format'] = "<:year>-<:month>-<:day>";
$lt['time_format'] = "<:hour>:<:minute>";
$lt['time_format_full'] = "<:hour>:<:minute>:<:second>";
$lt['date_time_format'] = "$lt[date_format] $lt[time_format]";
$lt['date_time_format_full'] = "$lt[date_format] $lt[time_format_full]";

$en['date_format_php'] = 'Y-m-d';
$en['time_format_php'] = 'H:i';
$en['time_format_full_php'] = 'H:i';
$en['datetime_format_php'] = 'Y-m-d H:i';
$en['datetime_format_full_php'] = 'Y-m-d H:i:s';

$en['date_format_js'] = 'yyyy-mm-dd';
$en['time_format_js'] = 'HH:MM';
$en['time_format_full_js'] = 'HH:MM:ss';
$en['datetime_format_js'] = 'yyyy-mm-dd HH:MM';
$en['datetime_format_full_js'] = 'yyyy-mm-dd HH:MM:ss';

$en['date_format_mysql'] = '%Y-%m-%d';
$en['time_format_mysql'] = '%H:%i';
$en['time_format_full_mysql'] = '%H:%i:%s';
$en['datetime_format_mysql'] = '%Y-%m-%d %H:%i';
$en['datetime_format_full_mysql'] = '%Y-%m-%d %H:%i:%s';
?>
