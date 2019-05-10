<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
		if(is_logged_in()){
            redirect('dash/home');
        }

        if ($this->validate()) {
            $identity = $this->input->post('identity');
            $password = $this->input->post('password');
            $user = $this->auth->credentials($identity, $password);
            if ($user) {
                return $this->auth->setUser($user);
            } else {
                redirect('auth/login', 'refresh');
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

    public function logout()
    {
        if($this->auth->logout()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Logged out!</div>');
            return redirect('auth/login');
        }
        return false;
    }

}
