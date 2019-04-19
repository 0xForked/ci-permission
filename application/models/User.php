<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        return $this->db->get("users")->result();
    }

    public function find($id)
    {
        return $this->db->get_where("users", ["id" => $id])->row(0);
    }

    public function add($data)
    {
        $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);
        return $this->db->insert('users', $data);
    }

    public function edit($data)
    {
        return $this->db->update('users', $data, ['id' => $data['id']]);
    }

    public function delete($id)
    {
        if ($this->find($id)) {
            $this->deleteRole($id);
            $delete = $this->db->delete('users', ['id' => $id]);
            return $delete;
        } else {
            return 0;
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
}

