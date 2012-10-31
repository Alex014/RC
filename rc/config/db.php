<?php

		$db['dev'] = array(
			'connectionString' => 'mysql:host=localhost;dbname=todos',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
      'session_table' => 'session'
		);
    
		$db['real'] = array(
			'connectionString' => 'mysql:host=sql6.nano.lv;dbname=todos',
			'emulatePrepare' => true,
			'username' => 'place2go_user',
			'password' => 'E2)-)0U6rV(w',
			'charset' => 'utf8',
      'session_table' => 'session'
		);
