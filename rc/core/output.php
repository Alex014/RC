<?php

/**
 * Class for formatted data output to screen
 * errors.php uses this class
 *
 * @author Alex014
 */
class output {
  
  public static $return = false;
  public static $delimiter = ' =&gt; ';
  public static $show_index = true;
  
  public static function show($data) {
    if($data) self::show_array ($data);
  }
  
  public static function show_array($data) {
    if(!function_exists('out_items')) {
      function out_items($items, $left_margin) {
        $delimiter = output::$delimiter;
        foreach($items as $key => $value) {
          if(output::$show_index || ($left_margin != 0))
            $index = "<strong>$key</strong>$delimiter";
          else
            $index = "&nbsp;";
          if(is_array($value)) {
            $text .= "<div style='margin-left: ".$left_margin."px;'>$index</div>";
            $text .= out_items($value, $left_margin + 20, $delimiter);
          }
          elseif(is_object($value)) {
            $class = get_class($value);
            $text .= "<div style='margin-left: ".$left_margin."px;'> $index [$class]</div>";
          }
          else {
            $text .= "<div style='margin-left: ".$left_margin."px;'> $index $value</div>";
          }
        }
        return $text;
      }
    }
    if(self::$return)
      return out_items($data, 0);
    else
      print out_items($data, 0);
  }
  
  public static function show_sql($sql) {
    $patterns = array(
        "/select/i",
        "/insert/i",
        "/update/i",
        "/delete/i",
        "/from/i",
        "/inner[\s]+join/i",
        "/left[\s]+join/i",
        "/right[\s]+join/i",
        "/where/i",
        "/order[\s]+by/i",
        "/limit/i",
        "/group by/i",
        "/([\s]on[\s])/i",
        "/\./i",
        "/\,/i",
        "/\(/i",
        "/\)/i"
    );
    $replacement = array(
        "<br/><b>SELECT</b><br/>",
        "<br/><b>INSERT</b>",
        "<br/><b>UPDATE</b>",
        "<br/><b>DELETE</b>",
        "<br/><b>FROM</b>",
        "<br/><b>INNER JOIN</b>",
        "<br/><b>LEFT JOIN</b>",
        "<br/><b>RIGHT JOIN</b>",
        "<br/><b>WHERE</b>",
        "<br/><b>ORDER BY</b>",
        "<br/><b>LIMIT</b>",
        "<br/><b>GROUP BY</b>",
        "<b>$1</b>",
        "<b>.</b>",
        "<b>,</b><br/>",
        "<b>(</b>",
        "<b>)</b>"
    );
    $sql = preg_replace($patterns, $replacement, $sql);
    $sql = "<code>$sql</code>";
    if(self::$return)
      return $sql;
    else
      print $sql;
  }
}
