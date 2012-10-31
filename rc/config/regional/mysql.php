<?php
//01.01.2001
$mysql['date_format_regex'] = "([\d]{4})-([\d]{2})-([\d]{2})";
//23:23
$mysql['time_format_regex'] = "([\d]{2}):([\d]{2}):{0,1}([\d]{0,2})";
//01.01.2001 23:23
$mysql['date_time_format_regex'] = "$mysql[date_format_regex][\s]+$mysql[time_format_regex]";


/**
 * Day - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$mysql['date_format_day_order'] = 3;
/**
 * Month - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$mysql['date_format_month_order'] = 2;
/**
 * Year - ORDER in "DATE_FORMAT_REGEX" regular expression
 */
$mysql['date_format_year_order'] = 1;
/**
 * Hour - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$mysql['time_format_hour_order'] = 1;
/**
 * Minute - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$mysql['time_format_minute_order'] = 2;
/**
 * Second - ORDER in "TIME_FORMAT_REGEX" regular expression
 */
$mysql['time_format_second_order'] = 3;


$mysql['date_format'] = "<:year>-<:month>-<:day>";
$mysql['time_format'] = "<:hour>:<:minute>";
$mysql['time_format_full'] = "<:hour>:<:minute>:<:second>";
$mysql['date_time_format'] = "$mysql[date_format] $mysql[time_format]";
$mysql['date_time_format_full'] = "$mysql[date_format] $mysql[time_format_full]";

$mysql['date_format_standart'] = 'yyyy-mm-dd';
?>
