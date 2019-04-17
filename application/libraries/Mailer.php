<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailer
{
    protected $CI;

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
        $this->CI = &get_instance();
        $this->init();

        $this->CI->load->library('email', $this->configs());
    }

    private function init()
    {
        $this->CI->config->load('mail');
        $this->protocol = $this->CI->config->item('mail_protocol');
        $this->host = $this->CI->config->item('mail_host');
        $this->port = $this->CI->config->item('mail_port');
        $this->user = $this->CI->config->item('mail_user');
        $this->password = $this->CI->config->item('mail_password');
        $this->type = $this->CI->config->item('mail_type');
        $this->charset = $this->CI->config->item('mail_charset');
        $this->sender = $this->CI->config->item('mail_sender_name');
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
        $this->CI->email->set_newline("\r\n");
        $this->CI->email->from($this->user, $this->sender);
        $this->CI->email->to($to);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        if ($this->CI->email->send()) {
            return true;
        } else {
            return false;
        }
    }

}