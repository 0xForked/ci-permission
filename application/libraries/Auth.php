<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{
    protected $CI;
    public $user = null;
    public $userID = null;
    public $userName = null;
    public $password = null;
    public $roles = 0;  // [ public $roles = null ] codeIgniter where_in() omitted for null.
    public $permissions = null;
    public $loginStatus = false;
    public $error = [];

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->init();
    }

    protected function init()
    {
        if ($this->CI->session->has_userdata("userID") && $this->CI->session->loginStatus) {
            $this->userID = $this->CI->session->userID;
            $this->userName = $this->CI->session->userName;
            $this->roles = $this->CI->session->roles;
            $this->loginStatus = true;
        }
        return;
    }

    // public function showLoginForm($data = [])
    // {
    //     return $this->CI->load->view("auth/login", $data);
    // }

    // public function login($request)
    // {
    //     if ($this->validate($request)) {
    //         $this->user = $this->credentials($this->userName, $this->password);
    //         if ($this->user) {
    //             return $this->setUser();
    //         } else {
    //             return $this->failedLogin($request);
    //         }
    //     }
    //     return false;
    // }

    // protected function validate($request)
    // {
    //     $this->CI->form_validation->set_rules('username', 'User Name', 'required');
    //     $this->CI->form_validation->set_rules('password', 'Password', 'required');
    //     if ($this->CI->form_validation->run() == TRUE) {
    //         /*$this->userName = $request["username"];
    //         $this->password = $request["password"];*/
    //         $this->userName = $this->CI->input->post("username", TRUE);
    //         $this->password = $this->CI->input->post("password", TRUE);
    //         return true;
    //     }
    //     return false;
    // }

    protected function credentials($username, $password)
    {
        $user = $this->CI->db->get_where("users", array("username" => $username, "status" => 1))->row(0);
        if($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    protected function setUser()
    {
        $this->userID = $this->user->id;
        $this->CI->session->set_userdata(array(
            "userID" => $this->user->id,
            "username" => $this->user->username,
            "roles" => $this->userHasRoles(),
            "loginStatus" => true
        ));
        return redirect("home");
    }

    // protected function failedLogin($request)
    // {
    //     $this->error["failed"] = "Username or Password Incorrect.";
    //     return $this->error;
    // }

    public function loginStatus()
    {
        return $this->loginStatus;
    }

    public function authenticate()
    {
        if (!$this->loginStatus()) {
            return redirect('login');
        }
        return true;
    }

    public function check($methods = 0)
    {
        if (is_array($methods) && count(is_array($methods))) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2))) {
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

    public function userID()
    {
        return $this->userID;
    }

    public function userName()
    {
        return $this->userName;
    }

    public function roles()
    {
        return $this->roles;
    }

    public function permissions()
    {
        return $this->permissions;
    }

    protected function userHasRoles()
    {
        return array_map(function ($item) {
            return $item["role_id"];
        }, $this->CI->db->get_where("user_has_role", array("user_id" => $this->userID()))->result_array());
    }

    public function userRoles()
    {
        return array_map(function ($item) {
            return $item["name"];
        }, $this->CI->db
            ->select("roles.*")
            ->from("roles")
            ->join("user_has_role", "roles.id = user_has_role.role_id", "inner")
            ->where(["user_has_role.user_id" => $this->userID(), "roles.status" => 1])
            ->get()->result_array());
    }

    public function userPermissions()
    {
        return array_map(function ($item) {
            return $item["name"];
        }, $this->CI->db
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
                if ($method == (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2))) {
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
                if ($method == (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2))) {
                    return true;
                }
            }
        }
        return $this->routeAccess();
    }

    public function routeAccess()
    {
        $this->check();
        $routeName = (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2)) . "-" . $this->CI->uri->segment(1);
        if ($this->CI->uri->segment(1) == 'home')
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
        $this->CI->session->unset_userdata(array("userID", "username", "loginStatus"));
        $this->CI->session->sess_destroy();
        return true;
    }
}