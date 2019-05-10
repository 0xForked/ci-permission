<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ForgotPasswordController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Mailer');
		if(is_logged_in()){
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
        $this->form_validation->set_rules('email', 'Email', 'required|mail_exists');
        if ($this->form_validation->run() == TRUE) {
            $forgot_code = $this->user->forgottenPassword($user_email);
            if (!$forgot_code) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed generate code</div>');
                redirect('auth/password/forgot', 'refresh');
            }

            $mail = $this->sendMail($user_email, $forgot_code);
            if (!$mail) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed send reset password link</div>');
                redirect('auth/password/forgot', 'refresh');
            }
            return true;
        }

        return false;
    }

    private function sendMail($to, $code)
    {
        $data = [
            'to' => $to,
            'subject' => 'CI-Permission Reset Password',
            'message' => '<a href="'.base_url().'auth/password/reset/'.$code.'">Link</a>'
        ];

        return $this->mailer->send($data);
    }

}
