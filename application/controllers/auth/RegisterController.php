<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegisterController extends CI_Controller
{

    const DEFAULT_OFFICE = 'FPsLogic';
    const DEFAULT_PHONE = '+6282200000000';
    const DEFAULT_ROLE = 5; //member

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
            $identity = $this->input->post('email');
            $password = $this->input->post('password');
            $user = $this->auth->credentials($identity, $password);
            if ($user) {
                return $this->auth->setUser($user);
            } else {
                return $this->failedCallback();
            }
        }

        $this->load->view('auth/register');
    }

    private function validate()
    {
        $this->form_validation->set_rules('first_name', 'First name', 'required');
        $this->form_validation->set_rules('last_name', 'Last name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|valid_password');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]');
        if ($this->form_validation->run() == TRUE) {
            $data = [
                'ip_address' => ($this->input->ip_address() == '::1') ? '127.0.0.1' : $this->input->ip_address(),
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'active' => 1,
                'company_id' => NULL,
                'phone' => self::DEFAULT_PHONE,
                'created_on' => time()
            ];

            $user = $this->user->add($data);
            $role = self::DEFAULT_ROLE;

            if ($user) {
                $user_id = $this->db->insert_id();
                $this->user->addRoles($user_id,  $role);
                $this->user->addDetail([
                    'user_id' => $user_id,
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                ]);
                $this->sendMail($data['email']);
            }

            return true;
        }

        return false;
    }

    private function failedCallback()
    {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed register new Account!</div>');
        redirect('auth/register', 'refresh');
    }

    private function sendMail($to)
    {
        $data = [
            'to' => $to,
            'subject' => 'Welcome',
            'message' => 'Welcome to CI-Permission'
        ];
        $this->mailer->send($data);
    }

}
