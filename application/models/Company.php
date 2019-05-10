<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        return $this->db->get("companies")->result();
    }

    public function find($id)
    {
        return $this->db->get_where("companies", ["id" => $id])->row(0);
    }

    public function findBy($key = "id", $value = NULL)
    {
        return $this->db->get_where("companies", [$key => $value])->result();
    }

    public function add($data)
    {
        return $this->db->insert('companies', $data);
    }

    public function edit($data)
    {
        return $this->db->update('companies', $data, ['id' => $data['id']]);
    }

    public function delete($id)
    {
        return $this->find($id) ? $this->db->delete('companies',  ['id' => $id]) : 0;
    }

    public function companyHasUsers($id)
    {
        return array_map(function($item) {
            return $item["user_id"];
        }, $this->db->get_where("users", ["company_id" => $id])->result_array());
    }

    public function companyHasUserDetails($id)
    {
        return array_map(function($item) {
            $company = new Company();
            return $company->findUser($item);
        }, $this->companyHasUsers($id));
    }

    public function findUser($id)
    {
        return $this->db->get_where("users", ["id" => $id])->row(0);
    }

    public function companyID($name)
    {
        return $this->db->get_where("companies", ["name" => $name])->row(0)->id;
    }
}