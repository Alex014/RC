<?php
/**
 * dirs: directory list, where to search classes
 * modules:
 * 'db' => 'mysql'
 * means that new db() class will be loaded from mysql file
 */
$autoload = array(
    'dirs' => array('lib'),
    'modules' => array(
        'db' => 'mysql'
    )
);

//Adding all first-level dirs of rc/lib to autoload dirs
$lib_dir_patch = dirname(__FILE__).'/../';
$dir_list = glob($lib_dir_patch. 'lib/*', GLOB_ONLYDIR);
for($i = 0; $i < count($dir_list); $i++)
  $autoload['dirs'][] = str_replace ($lib_dir_patch, '', $dir_list[$i]);

?>
