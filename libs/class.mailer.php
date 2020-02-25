<?php

require 'libs/phpmailer/PHPMailerAutoload.php';

class MAILER extends PHPMailer {

    public function config($config = array()) {
        if ($config['isSMTP']) {
            $this->isSMTP();
            $this->CharSet = 'UTF-8';
            $this->SMTPAuth = $config["SMTPAuth"];
            $this->SMTPSecure = $config['SMTPSecure'];
            $this->Host = $config['Host'];
            $this->Port = $config['Port'];
            $this->Username = $config["Username"];
            $this->Password = $config['Password'];
            $this->From = $config['From'];
            $this->FromName = $config['FromName'];
        }
    }

    public $confMail = array(
        'gmail' => array(
            'isSMTP' => true,
            'SMTPAuth' => true,
            'SMTPSecure' => 'ssl',
            'Host' => 'smtp.gmail.com',
            'Port' => 465,
            'Username' => "rafael.gonzalez.1737@gmail.com",
            'Password' => "carontell12345",
            'From' => 'rafael.gonzalez.1737@gmail.com',
            'FromName' => 'Administrador'
        ),
        'hotmail' => array(
            'isSMTP' => true,
            'SMTPAuth' => true,
            'SMTPSecure' => 'tls',
            'Host' => 'smtp.live.com',
            'Port' => 25,
            'Username' => "sasuke_22rafa@hotmail.com",
            'Password' => "carontell12345",
            'From' => 'sasuke_22rafa@hotmail.com',
            'FromName' => 'Administrador'
        )
    );

}
