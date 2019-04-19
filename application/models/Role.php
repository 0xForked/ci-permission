<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        return $this->db->get("roles")->result();
    }

    public function find($id)
    {
        return $this->db->get_where("roles", ["id" => $id])->row(0);
    }

    public function whereNot(Array $id)
    {
        $this->db->select('*');
        $this->db->from('roles');
        $this->db->where_not_in('id', $id);
        return array_map(function($item) {
            return $item;
        }, $this->db->get()->result());
    }

    public function add($data)
    {
        return $this->db->insert('roles', $data);
    }

    public function edit($data)
    {
        return $this->db->update('roles', $data, ['id' => $data['id']]);
    }

    public function delete($id)
    {
        if ($this->find($id)) {
            $this->deletePermissions($id);
            $delete = $this->db->delete('roles', ['id' => $id]);
            return $delete;
        } else {
            return 0;
        }
    }

    public function addPermissions($role_id, $permissions)
    {
        $data["role_id"] = $role_id;
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                $data["permission_id"] = $permission;
                $this->addPermission($data);
            }
        }
        else {
            $data["permission_id"] = $permissions;
            $this->addPermission($data);
        }
        return 1;
    }

    public function addPermission($data)
    {
        return $this->db->insert('role_has_permission', $data);
    }

    public function editPermissions($role_id, $permissions)
    {
        if($this->deletePermissions($role_id, $permissions)) {
            $this->addPermissions($role_id, $permissions);
        }
        return 1;
    }

    public function deletePermissions($role_id, $permissions = null)
    {
        return $this->db->delete('role_has_permission', ['role_id' => $role_id]);
    }

    public function roleHasPermissions($id)
    {
        return array_map(function($item) {
            return $item["permission_id"];
        }, $this->db->get_where("role_has_permission", ["role_id" => $id])->result_array());
    }

    public function roleHasPermissionDetails($id)
    {
        return array_map(function($item) {
            $role = new Role();
            return $role->findPermission($item);
        }, $this->roleHasPermissions($id));
    }

    public function findPermission($id)
    {
        return $this->db->get_where("permissions", ["id" => $id])->row(0);
    }

    public function roleID($name)
    {
        return $this->db->get_where("roles", ["name" => $name])->row(0)->id;
    }
}