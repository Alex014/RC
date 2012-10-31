<?php
//01.01.2001
$standart['date_format_regex'] = "([\d]{4})-([\d]{2})-([\d]{2})";
//23:23
$standart['time_format_regex'] = "([\d]{2}):([\d]{2}):{0,1}([\d]{0,2})";
//01.01.2001 23:23
$standart['date_time_format_regex'] = "$standart[date_format_regex][\s]+$standart[time_format_regex]";


/**
 * Day - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$standart['date_format_day_order'] = 3;
/**
 * Month - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$standart['date_format_month_order'] = 2;
/**
 * Year - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$standart['date_format_year_order'] = 1;
/**
 * Hour - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$standart['time_format_hour_order'] = 1;
/**
 * Minute - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$standart['time_format_minute_order'] = 2;
/**
 * Second - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$standart['time_format_second_order'] = 3;


$standart['date_format'] = "<:year>-<:month>-<:day>";
$standart['time_format'] = "<:hour>:<:minute>";
$standart['time_format_full'] = "<:hour>:<:minute>:<:second>";
$standart['date_time_format'] = "$standart[date_format] $standart[time_format]";
$standart['date_time_format_full'] = "$standart[date_format] $standart[time_format_full]";

$standart['date_format_standart'] = 'yyyy-mm-dd';
?>
