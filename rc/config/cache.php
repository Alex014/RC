<?php
$cache = array(
    
    'modules' => array(
      'frontend' => array(
          'enabled' => true,
          'adapter' => 'Files'
      )
    ),
    
    'classes' => array(
        '1min' => 60,
        '5min' => 300,
        '10min' => 600,
        '30min' => 1800,
        '1h' => 3600,
        '2h' => 7200,
        '6h' => 3600*6,
        '12h' => 3600*12,
        '24h' => 3600*24
    )
);