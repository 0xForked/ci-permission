<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('date');
    }

    public function all()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_detail', 'users_detail.user_id = users.id');
        return $this->db->get()->result();
    }

    public function find($id = NULL, $email = NULL)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_detail', 'users_detail.user_id = users.id');
        if ($id)  $this->db->where('users.id', $id);
        if ($email) $this->db->where('users.email', $email);
        return $this->db->get()->row();
    }

    public function findBy($key = NULL, $value = NULL)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_detail', 'users_detail.user_id = users.id');
        if ($key && $value)  $this->db->where($key, $value); else show_404();
        return $this->db->get()->row();
    }


    public function add($data)
    {
        $data["password"] = $this->auth->hashPassword($data["password"]);
        return $this->db->insert('users', $data);
    }

    public function addDetail($data)
    {
        return $this->db->insert('users_detail', $data);
    }

    public function edit($data)
    {
        return $this->db->update('users', $data, ['id' => $data['id']]);
    }

	public function updateLastLogin($id)
	{
        $this->db->update('users', ['last_login' => time()], ['id' => $id]);
        return $this->db->affected_rows() == 1;
    }

    public function clearForgottenPasswordCode($id)
    {
        $data = [
            'forgotten_password_selector' => NULL,
            'forgotten_password_code' => NULL,
            'forgotten_password_time' => NULL
        ];
        $this->db->update('users', $data, ['id' => $id]);
        return $this->db->affected_rows() == 1;
    }

    public function clearRememberCode($id) {
		$data = [
			'remember_selector' => NULL,
			'remember_code' => NULL
		];
		$this->db->update('users', $data, ['id' => $id]);
		return $this->db->affected_rows() == 1;
	}

    public function clearLoginAttempts(
        $email,
        $old_attempts_expire_period = 86400,
        $ip_address = NULL
    ) {
		if (TRACK_LOGIN_ATTEMPTS) {
			$old_attempts_expire_period = max($old_attempts_expire_period, ATTEMPTS_LOCKOUT_TIME);
			$this->db->where('login', $email);
			if (TRACK_LOGIN_IP_ADDRESS) {
				if (!isset($ip_address)) {
                    $ip = $this->input->ip_address();
					$ip_address = ($ip == '::1') ? '127.0.0.1' : $ip;
				}
				$this->db->where('ip_address', $ip_address);
			}
			$this->db->or_where('time <', time() - $old_attempts_expire_period, FALSE);
			return $this->db->delete('user_login_attempts');
		}
		return FALSE;
    }

    public function increaseLoginAttempts($email)
	{
		if (TRACK_LOGIN_ATTEMPTS) {
			$data = ['ip_address' => '', 'login' => $email, 'time' => time()];
			if (TRACK_LOGIN_IP_ADDRESS) {
                $ip = $this->input->ip_address();
				$data['ip_address'] = ($ip == '::1') ? '127.0.0.1' : $ip;
			}
			return $this->db->insert('user_login_attempts', $data);
		}
		return FALSE;
	}

    public function getAttemptsNum($email, $ip_address = NULL)
	{
		if (TRACK_LOGIN_ATTEMPTS)
		{
			$this->db->where('login', $email);
			if (TRACK_LOGIN_IP_ADDRESS)
			{
				if (!isset($ip_address))
				{
					$ip = $this->input->ip_address();
					$ip_address = ($ip == '::1') ? '127.0.0.1' : $ip;
				}
				$this->db->where('ip_address', $ip_address);
			}
			$this->db->where('time >', time() - ATTEMPTS_LOCKOUT_TIME, FALSE);
			$qres = $this->db->get('user_login_attempts');
			return $qres->num_rows();
		}
		return 0;
    }

    public function delete($id)
    {
        if ($this->find($id)) {
            $this->deleteRole($id);
            $delete = $this->db->delete('users_detail', ['user_id' => $id]);
            $delete = $this->db->delete('users', ['id' => $id]);
            return $delete;
        } else {
            return FALSE;
        }
    }

    public function addRoles($user_id, $roles)
    {
        $data["user_id"] = $user_id;
        if (is_array($roles)) {
            foreach ($roles as $role) {
                $data["role_id"] = $role;
                $this->addRole($data);
            }
        }
        else {
            $data["role_id"] = $roles;
            $this->addRole($data);
        }
        return 1;
    }

    public function addRole($data)
    {
        return $this->db->insert('user_has_role', $data);
    }

    public function editRoles($user_id, $roles)
    {
        if($this->deleteRole($user_id, $roles))
            $this->addRoles($user_id, $roles);
        return 1;
    }

    public function deleteRole($user_id, $roles = null)
    {
        return $this->db->delete('user_has_role', ['user_id' => $user_id]);
    }

    public function userHasRoles($id)
    {
        return array_map(function($item) {
            return $item["role_id"];
        }, $this->db->get_where("user_has_role", ["user_id" => $id])->result_array());
    }

    public function userHasRoleDetails($id)
    {
        return array_map(function($item){
            $user = new User();
            return $user->findRole($item);
        }, $this->userHasRoles($id));
    }

    public function findRole($id)
    {
        return $this->db->get_where("roles", ["id" => $id])->row(0);
    }

    public function usersWithRolesAndHasCompany(Array $id, $company_id = null)
    {
        $this->db->select('users.id, users.username, users.email, users.first_name, users.active');
        $this->db->select('users.last_name, users.company_id, user_has_role.role_id');
        $this->db->from('users');
        $this->db->join('user_has_role', 'users.id = user_has_role.user_id');
        $this->db->where_in('user_has_role.role_id', $id);
        if ((Boolean)$company_id) {
            $this->db->where('users.company_id', $company_id);
        }

        return array_map(function($item) {
            return $item;
        }, $this->db->get()->result());
    }

    public function forgottenPassword($email)
    {
        $token = $this->auth->generateSelectorValidatorCouple(20, 80);
		$update = [
			'forgotten_password_selector' => $token->selector,
			'forgotten_password_code' => $token->validator_hashed,
			'forgotten_password_time' => time()
		];
		$this->db->update('users', $update, ['email' => $email]);
		if ($this->db->affected_rows() === 1) {
			return $token->user_code;
		} else {
			return FALSE;
		}
    }

    public function setPassword($email, $password)
	{
		$hash = $this->auth->hashPassword($password);
		if ($hash === FALSE){
			return FALSE;
        }

		$data = [
			'password' => $hash,
			'remember_code' => NULL,
			'forgotten_password_code' => NULL,
			'forgotten_password_time' => NULL
		];
		$this->db->update('users', $data, ['email' => $email]);
		return $this->db->affected_rows() == 1;
	}

    public function findByForgottenPasswordCode($code)
	{
        $token = $this->auth->retrieveSelectorValidatorCouple($code);
		$user = $this->db->get_where("users", ['forgotten_password_selector' => $token->selector], 1)->row();
		if ($user) {
			// Check the hash against the validator
			if ( $this->auth->verifyPassword($token->validator, $user->forgotten_password_code)) {
				return $user;
			}
		}
		return FALSE;
	}

	public function deactivate($id = NULL)
	{
        $token = $this->auth->generateSelectorValidatorCouple(20, 40);
        $activation_code = $token->user_code;
		$data = [
		    'activation_selector'   => $token->selector,
		    'activation_code'       => $token->validator_hashed,
		    'active'                => 0
        ];
        $this->db->update('users', $data, ['id' => $id]);
        $return = $this->db->affected_rows() == 1;
		if ($return) {
            return $activation_code;
        }
        return FALSE;
    }


}

