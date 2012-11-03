<?php
return
/**
 *
 * @param string $src_filename
 * @param string $dst_filename
 * @param type $x1
 * @param type $x2
 * @param type $y1
 * @param type $y2
 * @param type $w
 * @param type $h
 * @param type $q
 * @return string 
 */
function ($src_filename, $dst_filename, $x1, $x2, $y1, $y2, $w, $h, $q = 70) {
    $src_ext = filemanager::filename_ext($src_filename);
    $dst_ext = filemanager::filename_ext($dst_filename);
    
    $src_filename = rtrim(FILEMANAGER::$upload_dir, '/').'/'.$src_filename;
    $dst_filename = rtrim(FILEMANAGER::$upload_dir, '/').'/'.$dst_filename;
    if(file_exists($dst_filename)) unlink ($dst_filename);
    
    switch ($src_ext) {
      case 'jpg': $src_img = imagecreatefromjpeg($src_filename);  break;
      case 'jpeg': $src_img = imagecreatefromjpeg($src_filename);  break;
      case 'png': $src_img = imagecreatefrompng($src_filename);  break;
      case 'gif':$src_img = imagecreatefromgif($src_filename);   break;
      default : return false; break;
    }
        
    $_w = ImageSX($src_img);
    $_h = ImageSY($src_img);
    $_wh = $_w/$_h;

    if(($w == '') && ($h == '')) {
      $w = $_w;
      $h = $_h;
    }
    elseif($w == '') {
      $w = round($h*$_wh);
    }
    elseif($h == '') {
      $h = round($w/$_wh);
    }

    $dst_img = ImageCreateTrueColor($w, $h); 
    $res = ImageCopyResampled($dst_img, $src_img, 0, 0, $x1, $y1, $w, $h, ($x2 - $x1), ($y2 - $y1)); 
    
    switch ($dst_ext) {
      case 'jpg': imagejpeg($dst_img, $dst_filename, $q);  break;
      case 'jpeg': imagejpeg($dst_img, $dst_filename, $q);  break;
      case 'png': imagepng($dst_img, $dst_filename);  break;
      case 'gif': imagegif($dst_img, $dst_filename);   break;
    }
    chmod($dst_filename, 0666);
    
    imagedestroy ( $dst_img );
    imagedestroy ( $src_img );
    
    return $dst_filename;
  };