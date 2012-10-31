<?php
$pmailer['exceptions'] = false;
 
// Just in case we need to relay to a different server,
// provide an option to use external mail server.
$pmailer['smtp_mode'] = 'enabled'; // enabled or disabled
$pmailer['smtp_host'] = 'localhost';
$pmailer['smtp_port'] = '25';
$pmailer['smtp_username'] = '';
$pmailer['smtp_password'] = '';