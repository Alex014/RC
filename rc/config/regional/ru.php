<?php
//01.01.2001
$ru['date_format_regex'] = "([\d]{2})\.([\d]{2})\.([\d]{4})";
//23:23
$ru['time_format_regex'] = "([\d]{2}):([\d]{2}):{0,1}([\d]{0,2})";
//01.01.2001 23:23
$ru['date_time_format_regex'] = "$ru[date_format_regex][\s]+$ru[time_format_regex]";


/**
 * Day - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$ru['date_format_day_order'] = 1;
/**
 * Month - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$ru['date_format_month_order'] = 2;
/**
 * Year - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$ru['date_format_year_order'] = 3;
/**
 * Hour - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$ru['time_format_hour_order'] = 1;
/**
 * Minute - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$ru['time_format_minute_order'] = 2;
/**
 * Second - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$ru['time_format_second_order'] = 3;

$ru['date_format'] = "<:day>.<:month>.<:year>";
$ru['time_format'] = "<:hour>:<:minute>";
$ru['time_format_full'] = "<:hour>:<:minute>:<:second>";
$ru['date_time_format'] = "$ru[date_format] $ru[time_format]";
$ru['date_time_format_full'] = "$ru[date_format] $ru[time_format_full]";

$en['date_format_php'] = 'd.m.Y';
$en['time_format_php'] = 'H:i';
$en['time_format_full_php'] = 'H:i';
$en['datetime_format_php'] = 'd.m.Y H:i';
$en['datetime_format_full_php'] = 'd.m.Y H:i:s';

$en['date_format_js'] = 'dd.mm.yyyy';
$en['time_format_js'] = 'HH:MM';
$en['time_format_full_js'] = 'HH:MM:ss';
$en['datetime_format_js'] = 'dd.mm.yyyy HH:MM';
$en['datetime_format_full_js'] = 'dd.mm.yyyy HH:MM:ss';

$en['date_format_mysql'] = '%d.%m.%Y';
$en['time_format_mysql'] = '%H:%i';
$en['time_format_full_mysql'] = '%H:%i:%s';
$en['datetime_format_mysql'] = '%d.%m.%Y %H:%i';
$en['datetime_format_full_mysql'] = '%d.%m.%Y %H:%i:%s';
?>
