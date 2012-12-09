<?php
return

  /**
   * Place a watermark onto image
   * 
   * @param type $filename - Filename
   * @param type $wfilename - Watermark filename
   * @param type $corner [lt - left top, rt - rigth top, rb - right bottom, lb - left bottom]
   * @param type $margin - margin of watermark
   */
  function ($filename, $wfilename, $corner = 'rt', $margin = 0, $q = 70) {
    $filename = rtrim(FILEMANAGER::$upload_dir, '/').'/'.$filename;
    $ext = filemanager::filename_ext($filename);
    $wfilename = rtrim(FILEMANAGER::$upload_dir, '/').'/'.$wfilename;
    $wext = filemanager::filename_ext($wfilename);

    switch ($ext) {
      case 'jpg': $image = imagecreatefromjpeg($filename);  break;
      case 'jpeg': $image = imagecreatefromjpeg($filename);  break;
      case 'png': $image = imagecreatefrompng($filename);  break;
      case 'gif':$image = imagecreatefromgif($filename);   break;
      default : return false; break;
    }
    
    switch ($wext) {
      case 'jpg': $wimage = imagecreatefromjpeg($wfilename);  break;
      case 'jpeg': $wimage = imagecreatefromjpeg($wfilename);  break;
      case 'png': $wimage = imagecreatefrompng($wfilename);  break;
      case 'gif':$wimage = imagecreatefromgif($wfilename);   break;
      default : return false; break;
    }
      
    $w = imagesx($image);
    $h = imagesy($image);
    $_w = imagesx($wimage);
    $_h = imagesy($wimage);
    
    switch ($corner) {
      case 'lt':
        $xpos = $margin;
        $ypos = $margin;
      break;
      case 'rt':
        $xpos = $w - $_w - $margin;
        $ypos = $margin;
      break;
      case 'rb':
        $xpos = $w - $_w - $margin;
        $ypos = $h - $_h - $margin;
      break;
      case 'lb':
        $xpos = $margin;
        $ypos = $h - $_h - $margin;
      break;
      default:
        $xpos = $margin;
        $ypos = $margin;
      break;
    }
    
    $res = imagecopy($image, $wimage, $xpos, $ypos, 0, 0, $_w, $_h);
    // Output and free memory
    switch ($ext) {
      case 'jpg': imagejpeg($image, $filename, $q);  break;
      case 'jpeg': imagejpeg($image, $filename, $q);  break;
      case 'png': imagepng($image, $filename, floor($q/10));  break;
      case 'gif':imagegif($image, $filename);   break;
      default : return false; break;
    }
    //var_dump($image);
    imagedestroy($image);
    imagedestroy($wimage);
  };