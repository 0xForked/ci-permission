<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        return $this->db->get("permissions")->result();
    }

    public function find($id)
    {
        return $this->db->get_where("permissions", ["id" => $id])->row(0);
    }

    public function add($data)
    {
        return $this->db->insert('permissions', $data);
    }

    public function delete($id)
    {
        return $this->find($id) ? $this->db->delete('permissions',  ['id' => $id]) : 0;
    }
}

