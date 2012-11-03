<?php

/**
 * Class filemanager
 *
 * @author Alex14
 */
class FILEMANAGER extends plugged {
  public static $upload_dir;
  
  /**
   * Content-type of a file
   *
   * @param type $name
   * @return type 
   */
  public static function f_type($name) {
    return $_FILES[$name]['type'];
  }
  

  public static function f_err($name) {
    return $_FILES[$name]['error'];
  }
  
  /**
   * Get file size in b - bytes, k - kilobytes, m - megabytes
   * @param type $filename
   * @param type $type
   * @return type 
   */
  public static function f_size($filename, $type = 'b', $precision = 2) {
    $sz = filesize(rtrim(self::$upload_dir, '/').'/'.$filename);
    switch($type) {
      case 'b': return $sz; break;
      case 'k': return round($sz/1024, $precision); break;
      case 'm': return round($sz/1024*1024, $precision); break;
    }
    return $sz;
  }
  
  /**
   * Uploaded filename
   * 
   * @param type $name
   * @return type 
   */
  public static function f_name($name) {
    return $_FILES[$name]['name'];
  }
  
  /**
   * Returns File name extension
   *
   * @param type $name
   * @return type 
   */
  public static function filename_ext($name) {
    $name_exploaded = explode('.', $name);
    return strtolower($name_exploaded[count($name_exploaded) - 1]);
  }
  
  /**
   * Uploaded file extension
   *
   * @param type $name
   * @return type 
   */
  public static function f_ext($name) {
    $_name = $_FILES[$name]['name'];
    $name_exploaded = explode('.', $_name);
    return strtolower($name_exploaded[count($name_exploaded) - 1]);
  }
  
  /**
   * Uploaded file main part (without extension)
   *
   * @param type $name
   * @return type 
   */
  public static function f_main($name) {
    $_name = $_FILES[$name]['name'];
    $name_exploaded = explode('.', $_name);
    if(count($name_exploaded) > 1)
      unset($name_exploaded[count($name_exploaded) - 1]);
    return implode('.', $name_exploaded);
  }
  
  /**
   * Upload file to a self::$upload_dir/$filename
   * 
   * @param type $name
   * @param type $filename
   * @return string 
   */
  public static function upload($name, $filename) {
    if(self::f_err($name) > 0) return false;
      
    $tmp_name = $_FILES[$name]['tmp_name'];
    $out_file = rtrim(self::$upload_dir, '/').'/'.$filename;
    if( move_uploaded_file($tmp_name, $out_file) ) {
      chmod ($out_file, 0666);
      return $out_file;
    }
    else
      return false;
  }
  
  public static function remove($filename) {
    unlink(rtrim(self::$upload_dir, '/').'/'.$filename);
  }


}

?>