<?php
return

function ($filename) {
  $ext = filemanager::filename_ext($filename);

  $filename = rtrim(FILEMANAGER::$upload_dir, '/').'/'.$filename;

  switch ($ext) {
    case 'jpg': $img = imagecreatefromjpeg($filename);  break;
    case 'jpeg': $img = imagecreatefromjpeg($filename);  break;
    case 'png': $img = imagecreatefrompng($filename);  break;
    case 'gif':$img = imagecreatefromgif($filename);   break;
    default : return false; break;
  }

  $sy = ImageSY($img);
  imagedestroy ( $img );
  return $sy;
  
};