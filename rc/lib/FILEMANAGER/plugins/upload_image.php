<?php
return
  
  /**
   * 
   *
   * @param type $name
   * @param type $filename Where to save a file FILEMANAGER::$upload_dir/$filename
   *  with extension
   * @param type $height - Result image height
   * @param type $width - Result image width
   * @param type $q - quality (Jpeg, PNG)
   * @return type false if error occured
   */
  function ($name, $filename, $height, $width, $q = 70) {
    $img_file = $_FILES[$name]['tmp_name'];
    $ext = filemanager::f_ext($name);
    if($img_file === false)
      return false;
    
    switch ($ext) {
      case 'jpg': $src_img = imagecreatefromjpeg($img_file);  break;
      case 'jpeg': $src_img = imagecreatefromjpeg($img_file);  break;
      case 'png': $src_img = imagecreatefrompng($img_file);  break;
      case 'gif':$src_img = imagecreatefromgif($img_file);   break;
      default : return false; break;
    }
        
    $w = ImageSX($src_img);
    $h = ImageSY($src_img);
    $wh = $w/$h;

    if($width == '') {
      $width = round($height*$wh);
    }
    elseif($height == '') {
      $height = round($width/$wh);
    }

    $dst_img = ImageCreateTrueColor($width, $height); 
    //print "$dst_img, $src_img, 0, 0, 0, 0, $width, $height, $w, $h";
    $res = ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $width, $height, $w, $h); 
    //var_dump($res);
        //die();
    $out_file = rtrim(FILEMANAGER::$upload_dir, '/').'/'.$filename;
    if(file_exists($out_file)) unlink ($out_file);
    
    switch ($ext) {
      case 'jpg': $img = imagejpeg($dst_img, $out_file, $q);  break;
      case 'jpeg': $img = imagejpeg($dst_img, $out_file, $q);  break;
      case 'png': $img = imagepng($dst_img, $out_file);  break;
      case 'gif': $img = imagegif($dst_img, $out_file);   break;
    }
    chmod($out_file, 0666);
    return $out_file;
  };