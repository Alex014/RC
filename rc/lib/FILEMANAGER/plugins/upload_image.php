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
    if($_FILES[$name]['tmp_name'] === false) return false;
    $img_file = $_FILES[$name]['tmp_name'];
    $ext = filemanager::f_ext($name);
    
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

    if(($width == '') && ($height == '')) {
      $width = $w;
      $height = $h;
    }
    elseif($width == '') {
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
      case 'jpg': imagejpeg($dst_img, $out_file, $q);  break;
      case 'jpeg': imagejpeg($dst_img, $out_file, $q);  break;
      case 'png': imagepng($dst_img, $out_file);  break;
      case 'gif': imagegif($dst_img, $out_file);   break;
    }
    
    imagedestroy ( $dst_img );
    imagedestroy ( $src_img );
    
    chmod($out_file, 0666);
    return $out_file;
  };