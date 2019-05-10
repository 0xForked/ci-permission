
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// rest password berdasarkan link dari email

class ResetPasswordController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Mailer');
		if(is_logged_in()){
            redirect('dash/home');
        }
    }

    public function index($code = null)
    {
        if (!$_POST && !$code) {
			show_404();
		}

        $validate = $this->validateToken($code);
        if ($validate) {
            if ($this->validateInput($validate)) {
                $identity = $validate->email;
                $password = $this->input->post('password');
                $user = $this->auth->credentials($identity, $password);
                if ($user) {
                    return $this->auth->setUser($user);
                } else {
                    redirect('auth/login', 'refresh');
                }
            }
        } else {
            show_404();
        }

		$this->load->view('auth/password/reset', compact('code'));
    }

    private function validateInput($data)
    {
        $this->form_validation->set_rules('password', 'Password', 'required|valid_password');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]');

        if ($this->form_validation->run() == TRUE) {
            $this->user->setPassword($data->email, $this->input->post('password'));
            $this->sendMail($data->email);
            return true;
        }

        return false;
    }

    private function validateToken($code)
    {
        $user = $this->user->findByForgottenPasswordCode($code);
		if (!is_object($user)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Password reset unsuccess user not found</div>');
			return FALSE;
		} else {
			if (FORGOT_PASSWORD_EXPIRATION > 0){
				//Make sure it isn't expired
				$expiration = FORGOT_PASSWORD_EXPIRATION;
				if (time() - $user->forgotten_password_time > $expiration) {
					//it has expired
					$this->user->clearForgottenPasswordCode($user->id);
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Token was expired</div>');
					return FALSE;
				}
			}
			return $user;
        }
        return FALSE;
    }

    private function sendMail($to)
    {
        $data = [
            'to' => $to,
            'subject' => 'CI-Permission Reset Password',
            'message' => 'Reset password success!'
        ];

        return $this->mailer->send($data);
    }

}
