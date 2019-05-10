<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation  extends CI_Form_validation
{

    protected $ci;

    public function __construct($rules = array())
    {
        parent::__construct($rules);
        $this->ci =& get_instance();
    }

    public function mail_exists($email)
    {
        $user = $this->ci->user->findBy('email', $email);
        if (!$user) {
			$this->ci->form_validation->set_message('mail_exists', 'Oops we couldnt find user with that address.');
			return FALSE;
        }
		return TRUE;
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
			$this->ci->form_validation->set_message('valid_password', 'The {field} field is required.');
			return FALSE;
		}
		// if (preg_match_all($regex_lowercase, $password) < 1)
		// {
		// 	$this->ci->form_validation->set_message('valid_password', 'The {field} field must be at least one lowercase letter.');
		// 	return FALSE;
		// }
		// if (preg_match_all($regex_uppercase, $password) < 1)
		// {
		// 	$this->ci->form_validation->set_message('valid_password', 'The {field} field must be at least one uppercase letter.');
		// 	return FALSE;
		// }
		// if (preg_match_all($regex_number, $password) < 1)
		// {
		// 	$this->ci->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');
		// 	return FALSE;
		// }
		// if (preg_match_all($regex_special, $password) < 1)
		// {
		// 	$this->ci->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
		// 	return FALSE;
		// }
		if (strlen($password) < 6)
		{
			$this->ci->form_validation->set_message('valid_password', 'The {field} field must be at least 6 characters in length.');
			return FALSE;
		}
		if (strlen($password) > 32)
		{
			$this->ci->form_validation->set_message('valid_password', 'The {field} field cannot exceed 32 characters in length.');
			return FALSE;
		}
		return TRUE;
	}

}