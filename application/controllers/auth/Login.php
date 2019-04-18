<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
		if($this->auth->loginStatus()){
            redirect('dash/home');
        }

        if ($this->validate()) {
            $identity = $this->input->post('identity');
            $password = $this->input->post('password');
            $user = $this->auth->credentials($identity, $password);
            if ($user) {
                return $this->auth->setUser($user);
            } else {
                return $this->failedCallback();
            }
        }

		$this->load->view('auth/login');
    }

    private function validate()
    {
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == TRUE) {
            return true;
        }
        return false;
    }

    private function failedCallback()
    {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Login failed, wrong identity or password</div>');
        redirect('auth/login', 'refresh');
    }

    public function logout()
    {
        if($this->auth->logout()) {
            return redirect('auth/login');
        }
        return false;
    }

}
