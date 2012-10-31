<?
// (c) YURiQUE (Yuriy Malchenko), 2005
// jmalchenko@gmail.com
//
// A very simple class with more natural cyrillic to latin transliteration.
//
// function Transliterate() takes 3 arguments - the string to transliterate itself,
// an encoding of the input string and an encoding of the result to get.
//
  class translit {
    
    public static function transliterate($str, $encIn = 'UTF-8', $encOut = 'UTF-8'){
      $cyr=array(
            "Щ",  "Ш", "Ч", "Ц","Ю", "Я", "Ж", "А","Б","В","Г","Д","Е","Ё","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х", "Ь","Ы","Ъ","Э","Є","Й",
            "щ",  "ш", "ч", "ц","ю", "я", "ж", "а","б","в","г","д","е","ё","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х", "ь","ы","ъ","э","є","й");
      $lat=array(
            "Shh","Sh","Ch","C","Ju","Ja","Zh","A","B","V","G","D","Je","Jo","Z","I","J","K","L","M","N","O","P","R","S","T","U","F","Kh","","Y","","E","Je","Ji",
            "shh","sh","ch","c","ju","ja","zh","a","b","v","g","d","je","jo","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","","y","","e","je","ji");

      $str = iconv($encIn, "UTF-8", $str);
      mb_internal_encoding("UTF-8");
      mb_regex_encoding("UTF-8");
      
      for($i=0; $i<count($cyr); $i++){
        $c_cyr = $cyr[$i];
        $c_lat = $lat[$i];
        $str = mb_eregi_replace($c_cyr, $c_lat, $str);
      }
      
      //$str = mb_eregi_replace("\W", "_", $str);
      
      return iconv("utf-8", $encOut, $str);
    }
  }
?>
