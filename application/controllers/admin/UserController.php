<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

    const TAG = 'user';
    const DEFAULT_OFFICE = 'FPsLogic';
    const DEFAULT_PASSWORD = 'secret';
    const DEFAULT_PHONE = '+6282200000000';

    public function __construct()
    {
       parent::__construct();
       $this->load->helper('auth');
    }

	public function index()
	{
        $title = self::TAG;
        $user_data = $this->user->all();

        $users = [];
        foreach ($user_data as $user) {
            $role = $this->user->userHasRoleDetails($user->id);
            $users[] = (object) [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'active' => $user->active,
                'role' => $role[0]
            ];
        }

		$this->load->view('admin/user/index', compact('title', 'users', 'roles'));
    }

    public function create()
    {
        $this->createView();
    }

    public function store()
    {
        $this->form_validation->set_rules('first_name', 'First name', 'required');
        $this->form_validation->set_rules('last_name', 'Last name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|is_unique[users.email]');
        if ($this->form_validation->run() == FALSE) {
            $this->createView();
        } else {
            $data = [
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => self::DEFAULT_PASSWORD,
                'active' => $this->input->post('status'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => self::DEFAULT_OFFICE,
                'phone' => self::DEFAULT_PHONE,
                'created_on' => time()
            ];

            $user = $this->user->add($data);
            $role = $this->input->post('role');

            if ($user) {
                $user_id = $this->db->insert_id();
                $this->user->addRoles($user_id,  $role);
            }

            redirect('dash/users', 'refresh');
        }
    }

    public function edit(Int $id)
    {
        $this->updateView($id);
    }

    public function update(Int $id)
    {
        $username = $this->input->post('username');
        $active = $this->input->post('status');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $role = $this->input->post('role');

        $user = $this->user->find($id);

        if ($user) {
            $this->user->editRoles($user->id, $role);
            $this->user->edit([
                'id' => $user->id,
                'username' => $username,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'active' => $active
            ]);

            redirect('dash/users', 'refresh');
        }
    }

    public function delete(Int $id)
    {
        $this->user->delete($id);
        redirect('dash/users', 'refresh');
    }

    private function createView()
    {
        $title = self::TAG;
        $roles = $this->role->all();
        $this->load->view('admin/user/create', compact('title', 'roles'));
    }

    private function updateView($id)
    {
        $title = self::TAG;
        $user = $this->user->find($id);
        $roles = $this->role->all();
        $user_has_role = $this->user->userHasRoles($id);
        $this->load->view('admin/user/edit', compact('title', 'user', 'roles', 'user_has_role'));
    }

    private function sendMail()
    {

    }
}
