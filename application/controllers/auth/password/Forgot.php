<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Mailer');
        if($this->session->userdata('loginStatus')){
            redirect('dash/home');
        }
    }

    public function index()
    {
        if ($this->validate()) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Success send reset password link</div>');
            redirect('auth/login', 'refresh');
        }

		$this->load->view('auth/password/forgot');
    }

    private function validate()
    {
        $user_email = $this->input->post('email');
        $this->form_validation->set_rules('email', 'Email', 'required');
        if ($this->form_validation->run() == TRUE) {
            $mail = $this->sendMail($user_email);
            if (!$mail) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed send reset password link</div>');
                redirect('auth/password/forgot', 'refresh');
            }
            return true;
        }

        return false;
    }

    private function sendMail($to)
    {
        $data = [
            'to' => $to,
            'subject' => 'CI-Permission Reset Password',
            'message' => 'ini secret link'
        ];

        return $this->mailer->send($data);
    }
}
