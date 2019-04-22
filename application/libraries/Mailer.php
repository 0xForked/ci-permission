<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailer
{
    protected $ci;

    private $protocol;
    private $host;
    private $port;
    private $user;
    private $password;
    private $type;
    private $charset;
    private $sender;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->init();

        $this->ci->load->library('email', $this->configs());
    }

    private function init()
    {
        $this->ci->config->load('mail');
        $this->protocol = $this->ci->config->item('mail_protocol');
        $this->host = $this->ci->config->item('mail_host');
        $this->port = $this->ci->config->item('mail_port');
        $this->user = $this->ci->config->item('mail_user');
        $this->password = $this->ci->config->item('mail_password');
        $this->type = $this->ci->config->item('mail_type');
        $this->charset = $this->ci->config->item('mail_charset');
        $this->sender = $this->ci->config->item('mail_sender_name');
    }

    public function send(Array $data)
    {
        $object = (Object)$data;
        $send = $this->do($object->to, $object->subject, $object->message);
        return $send;
    }

    private function configs()
    {
        return [
            'protocol'  => $this->protocol,
            'smtp_host' => $this->host,
            'smtp_port' => $this->port,
            'smtp_user' => $this->user,
            'smtp_pass' => $this->password,
            'mailtype'  => $this->type,
            'charset'   => $this->charset
        ];
    }

    private function do($to, $subject, $message)
    {
        $this->ci->email->set_newline("\r\n");
        $this->ci->email->from($this->user, $this->sender);
        $this->ci->email->to($to);
        $this->ci->email->subject($subject);
        $this->ci->email->message($message);
        if ($this->ci->email->send()) {
            return true;
        } else {
            return false;
        }
    }

}