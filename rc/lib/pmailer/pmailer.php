<?php
require_once(dirname(__FILE__).'/class.phpmailer.php');

class pmailer extends PHPMailer
{
    var $priority = 3;
    var $to_name;
    var $to_email;
    var $From = null;
    var $FromName = null;
    var $Sender = null;
    
    public function __construct($params) {
        $pmailer = get_config('pmailer');
        
        $pmailer = array_merge($pmailer, $params);
        
        parent::__construct($pmailer['exceptions']);
        
        if($pmailer['smtp_mode'] == 'enabled')
        {
            $this->Host = $pmailer['smtp_host'];
            $this->Port = $pmailer['smtp_port'];
            if($pmailer['smtp_username'] != '')
            {
                $this->SMTPAuth = true;
                $this->Username = $pmailer['smtp_username'];
                $this->Password = $pmailer['smtp_password'];
            }
            $this->Mailer = "smtp";
        }
        $this->Priority = $this->priority;
    }
}

?>