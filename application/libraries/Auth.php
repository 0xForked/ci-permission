<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{
    /**
	 * CodeIgniter Instace
	 *
	 * @var object
	 */
    protected $ci;

    public $uid = null;
    public $username = null;
    public $email = null;
    public $roles = 0;
    public $company = null;
    public $permissions = null;
    public $loginStatus = false;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->helper('date');
        $this->ci->load->model('user');
        $this->init();
    }

    protected function init()
    {
        if ($this->ci->session->has_userdata("uid") && $this->ci->session->loginStatus) {
            $this->uid = $this->ci->session->uid;
            $this->username = $this->ci->session->username;
            $this->email = $this->ci->session->email;
            $this->roles = $this->ci->session->roles;
            $this->company = $this->ci->session->company;
            $this->loginStatus = true;
        }
        return;
    }

    public function credentials($identity, $password)
    {
        $limit = 1;
        $status = 1;
        $user = $this->ci->db->get_where("users", ["username" => $identity, "active" => $status], $limit)
        ->row();
        if (!$user) {
            $user = $this->ci->db->get_where("users", ["email" => $identity, "active" => $status], $limit)
            ->row();
        }

        if ($this->isMaxLoginAttemptsExceeded($user->email)) {
            $this->ci->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">To much failed attemp, do next time</div>');
			return FALSE;
		}

        if ($user && password_verify($password, $user->password)) {
            $this->ci->user->updateLastLogin($user->id);
            $this->ci->user->clearLoginAttempts($user->email);
			$this->ci->user->clearForgottenPasswordCode($user->id);
            return $user;
        }

        $this->ci->user->increaseLoginAttempts($user->email);
        $this->ci->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Login failed, wrong email or password</div>');
        return FALSE;
    }

    public function setUser($user)
    {
        $user = (Object)$user;
        $this->uid = $user->id;
        $this->ci->session->set_userdata(array(
            "uid" => $user->id,
            "username" => $user->username,
            "email" => $user->email,
            "roles" => $this->userHasRoles(),
            "company" => $user->company_id,
            "loginStatus" => true
        ));
        return redirect("dash/home");
    }

    public function loginStatus()
    {
        return $this->loginStatus;
    }

    public function authenticate()
    {
        if (!$this->loginStatus()) {
            return redirect('auth/login');
        }
        return true;
    }

    public function check($methods = 0)
    {
        if (is_array($methods) && count(is_array($methods))) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->ci->uri->segment(2)) ? "index" : $this->ci->uri->segment(2))) {
                    return $this->authenticate();
                }
            }
        }
        return $this->authenticate();
    }

    public function guest()
    {
        return !$this->loginStatus();
    }

    public function uid()
    {
        return $this->uid;
    }

    public function username()
    {
        return $this->username;
    }

    public function email()
    {
        return $this->email;
    }

    public function roles()
    {
        return $this->roles;
    }

    public function company()
    {
        return $this->company;
    }

    public function permissions()
    {
        return $this->permissions;
    }

    protected function userHasRoles()
    {
        return array_map(function ($item) {
            return $item["role_id"];
        }, $this->ci->db->get_where("user_has_role", array("user_id" => $this->uid()))->result_array());
    }

    public function userRole()
    {
        return $this->userRoles($this->roles[0])[0];
    }

    public function userRoles()
    {
        return array_map(function ($item) {
            return $item["title"];
        }, $this->ci->db
            ->select("roles.*")
            ->from("roles")
            ->join("user_has_role", "roles.id = user_has_role.role_id", "inner")
            ->where(["user_has_role.user_id" => $this->uid()])
            ->get()->result_array());
    }

    public function userPermissions()
    {
        return array_map(function ($item) {
            return $item["title"];
        }, $this->ci->db
        ->select("permissions.*")
        ->from("permissions")
        ->join("role_has_permission", "permissions.id = role_has_permission.permission_id", "inner")
        ->where_in("role_has_permission.role_id", $this->roles())
        ->group_by("role_has_permission.permission_id")
        ->get()->result_array());
    }

    public function only($methods = array())
    {
        if (is_array($methods) && count(is_array($methods))) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->ci->uri->segment(2)) ? "index" : $this->ci->uri->segment(2))) {
                    return $this->routeAccess();
                }
            }
        }
        return true;
    }

    public function except($methods = array())
    {
        if (is_array($methods) && count(is_array($methods))) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->ci->uri->segment(2)) ? "index" : $this->ci->uri->segment(2))) {
                    return true;
                }
            }
        }
        return $this->routeAccess();
    }

    public function routeAccess()
    {
        $this->check();
        $routeName = (is_null($this->ci->uri->segment(2)) ? "index" : $this->ci->uri->segment(2)) . "-" . $this->ci->uri->segment(1);
        if ($this->ci->uri->segment(1) == 'dash')
            return true;
        if($this->can($routeName))
            return true;
        return redirect('exceptions/custom_404', 'refresh');
    }

    public function hasRole($roles, $requireAll = false)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->checkRole($role) && !$requireAll)
                    return true;
                elseif (!$this->checkRole($role) && $requireAll) {
                    return false;
                }
            }
        }
        else {
            return $this->checkRole($roles);
        }
        // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
        // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
        // Return the value of $requireAll;
        return $requireAll;
    }

    public function checkRole($role)
    {
        return in_array($role, $this->userRoles());
    }

    public function can($permissions, $requireAll = false)
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($this->checkPermission($permission) && !$requireAll)
                    return true;
                elseif (!$this->checkPermission($permission) && $requireAll) {
                    return false;
                }
            }
        }
        else {
            return $this->checkPermission($permissions);
        }
        // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
        // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
        // Return the value of $requireAll;
        return $requireAll;
    }

    public function checkPermission($permission)
    {
        return in_array($permission, $this->userPermissions());
    }

    public function logout()
    {
        $this->ci->user->clearRememberCode($this->uid());
        $this->ci->user->clearForgottenPasswordCode($this->uid());
        $this->ci->session->unset_userdata([
            "uid",
            "username",
            "email",
            "loginStatus",
            "roles",
            "company"
        ]);
        $this->ci->session->sess_destroy();
        session_start();
		$this->ci->session->sess_regenerate(TRUE);
        return true;
    }


    /**
	 * Generate a random selector/validator couple
     * This function from ion_auth by @benedmunds
     *
	 * This is a user code
	 *
	 * @param $selector_size int	size of the selector token
	 * @param $validator_size int	size of the validator token
	 *
	 * @return object
	 * 			->selector			simple token to retrieve the user (to store in DB)
	 * 			->validator_hashed	token (hashed) to validate the user (to store in DB)
	 * 			->user_code			code to be used user-side (in cookie or URL)
	 */
	public function generateSelectorValidatorCouple(
        $selector_size = 40,
        $validator_size = 128
    ) {
		// The selector is a simple token to retrieve the user
		$selector = $this->randomToken($selector_size);
		// The validator will strictly validate the user and should be more complex
		$validator = $this->randomToken($validator_size);
		// The validator is hashed for storing in DB (avoid session stealing in case of DB leaked)
		$validator_hashed = $this->hashPassword($validator);
		// The code to be used user-side
		$user_code = "$selector.$validator";
		return (object) [
			'selector' => $selector,
			'validator_hashed' => $validator_hashed,
			'user_code' => $user_code,
		];
    }

    /** Generate a random token
     * This function from ion_auth by @benedmunds
     *
	 * Inspired from http://php.net/manual/en/function.random-bytes.php#118932
	 *
	 * @param int $result_length
	 * @return string
	 */
	protected function randomToken(
        $result_length = 32
    ) {
		if(!isset($result_length) || intval($result_length) <= 8 ){
			$result_length = 32;
		}
		// Try random_bytes: PHP 7
		if (function_exists('random_bytes')) {
			return bin2hex(random_bytes($result_length / 2));
		}
		// Try mcrypt
		if (function_exists('mcrypt_create_iv')) {
			return bin2hex(mcrypt_create_iv($result_length / 2, MCRYPT_DEV_URANDOM));
		}
		// Try openssl
		if (function_exists('openssl_random_pseudo_bytes')) {
			return bin2hex(openssl_random_pseudo_bytes($result_length / 2));
		}
		// No luck!
		return FALSE;
    }

    /**
	 * Hashes the password to be stored in the database.
     * This function from ion_auth by @benedmunds
	 *
	 * @param string $password
	 *
	 * @return false|string
	 * @author Mathew
	 */
	public function hashPassword(
        $password
    ) {
		// Check for empty password, or password containing null char, or password above limit
		// Null char may pose issue: http://php.net/manual/en/function.password-hash.php#118603
		// Long password may pose DOS issue (note: strlen gives size in bytes and not in multibyte symbol)
		if (empty($password) || strpos($password, "\0") !== FALSE ||
			strlen($password) > MAX_PASSWORD_SIZE_BYTES) {
			return FALSE;
		}
		$algo = PASSWORD_BCRYPT;
		//$params = BCRYPT_COST; // default 60 characters, define 12 characters
		if ($algo !== FALSE)
		{
			return password_hash($password, $algo /**, $params*/);
		}
		return FALSE;
    }

    /**
	 * This function takes a password and validates it
	 * against an entry in the users table.
	 *
	 * @param string	$password
	 * @param string	$hash_password_db
	 * @param string	$identity			optional @deprecated only for BC SHA1
	 *
	 * @return bool
	 * @author Mathew
	 */
	public function verifyPassword(
        $password,
        $hash_password_db
    ) {
		// Check for empty id or password, or password containing null char, or password above limit
		// Null char may pose issue: http://php.net/manual/en/function.password-hash.php#118603
		// Long password may pose DOS issue (note: strlen gives size in bytes and not in multibyte symbol)
		if (empty($password) || empty($hash_password_db) || strpos($password, "\0") !== FALSE
			|| strlen($password) > MAX_PASSWORD_SIZE_BYTES) {
			return FALSE;
		}
		// password_hash always starts with $
		if (strpos($hash_password_db, '$') === 0) {
			return password_verify($password, $hash_password_db);
        }

        return FALSE;
	}


	/**
	 * is_max_login_attempts_exceeded
     * This function from ion_auth by @benedmunds
     *
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string      $identity   user's identity
	 * @param string|null $ip_address IP address
	 *                                Only used if track_login_ip_address is set to TRUE.
	 *                                If NULL (default value), the current IP address is used.
	 *                                Use get_last_attempt_ip($identity) to retrieve a user's last IP
	 *
	 * @return boolean
	 */
	public function isMaxLoginAttemptsExceeded(
        $identity,
        $ip_address = NULL
    ) {
		if (TRACK_LOGIN_ATTEMPTS)
		{
			$max_attempts = MAXIMUM_LOGIN_ATTEMPTS;
			if ($max_attempts > 0)
			{
                $attempts = $this->ci->user->getAttemptsNum($identity, $ip_address);
				return $attempts >= $max_attempts;
			}
		}
		return FALSE;
    }

    /**
	 * Retrieve remember cookie info
     * This function from ion_auth by @benedmunds
	 *
	 * @param $user_code string	A user code of the form "selector.validator"
	 *
	 * @return object
	 * 			->selector		simple token to retrieve the user in DB
	 * 			->validator		token to validate the user (check against hashed value in DB)
	 */
	public function retrieveSelectorValidatorCouple($user_code)
	{
		if ($user_code) {
			$tokens = explode('.', $user_code);
			if (count($tokens) === 2) {
				return (object) [
					'selector' => $tokens[0],
					'validator' => $tokens[1]
				];
			}
		}
		return FALSE;
	}

}
