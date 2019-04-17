<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Mailer');
    }

    public function index()
    {
		$this->load->view('auth/password/forgot');
    }

    public function forgot()
    {
        $user_email = $this->input->post('email');
        $this->form_validation->set_rules('email', 'Email', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/password/forgot');
        } else {
            $data = [
                'to' => $user_email,
                'subject' => 'CI-Permission Reset Password',
                'message' => 'ini secret link'
            ];

            $mail = $this->mailer->send($data);
            if ($mail) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Success send reset password link</div>');
                redirect('auth/login', 'refresh');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed send reset password link</div>');
                redirect('auth/password/forgot', 'refresh');
            }
        }

    }
}
