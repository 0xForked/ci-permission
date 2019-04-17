<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller
{

    const DEFAULT_OFFICE = 'FPsLogic';
    const DEFAULT_PHONE = '+6282200000000';
    const DEFAULT_ROLE = 5; //member

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
        $this->form_validation->set_rules('password', 'Password', 'required|callback_valid_password');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]');
        if ($this->form_validation->run() == TRUE) {
            $data = [
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'active' => 1,
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => self::DEFAULT_OFFICE,
                'phone' => self::DEFAULT_PHONE,
                'created_on' => time()
            ];

            $user = $this->user->add($data);
            $role = self::DEFAULT_ROLE;

            if ($user) {
                $user_id = $this->db->insert_id();
                $this->user->addRoles($user_id,  $role);
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

    public function valid_password($password = '')
	{
		$password = trim($password);
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
		if (empty($password))
		{
			$this->form_validation->set_message('valid_password', 'The {field} field is required.');
			return FALSE;
		}
		// if (preg_match_all($regex_lowercase, $password) < 1)
		// {
		// 	$this->form_validation->set_message('valid_password', 'The {field} field must be at least one lowercase letter.');
		// 	return FALSE;
		// }
		// if (preg_match_all($regex_uppercase, $password) < 1)
		// {
		// 	$this->form_validation->set_message('valid_password', 'The {field} field must be at least one uppercase letter.');
		// 	return FALSE;
		// }
		// if (preg_match_all($regex_number, $password) < 1)
		// {
		// 	$this->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');
		// 	return FALSE;
		// }
		// if (preg_match_all($regex_special, $password) < 1)
		// {
		// 	$this->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
		// 	return FALSE;
		// }
		if (strlen($password) < 6)
		{
			$this->form_validation->set_message('valid_password', 'The {field} field must be at least 6 characters in length.');
			return FALSE;
		}
		if (strlen($password) > 32)
		{
			$this->form_validation->set_message('valid_password', 'The {field} field cannot exceed 32 characters in length.');
			return FALSE;
		}
		return TRUE;
	}
}
