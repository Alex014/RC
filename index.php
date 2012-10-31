<?php
require_once dirname(__FILE__).'/rc/rc_loader.php';

/**
 * Loading the framework
 * index.php?url=/url/path
 */

rc_loader::load(array(
    'url_type' => 'full',
    'get_url_index' => 'url',
    'path' => array(
        '.*' => array('application' => 'frontend', 'base_url' => '/')
    )
));