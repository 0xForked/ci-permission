<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RoleController extends CI_Controller {

    const TAG = 'role';

    public function __construct()
    {
       parent::__construct();

        // check user isLoggedIn
        $this->auth->routeAccess();

        // check if user is ...
        // if not redirect to user role page
        if (!has_roles('root')) {
            show_404();
        }
    }

	public function index()
	{
        $title = self::TAG;
        $roles = $this->role->all();
		$this->load->view('dash/role/index', compact('title', 'roles'));
    }

    public function create()
    {
      $this->createView();
    }

    public function store()
    {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->createView();
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
            ];

            $role = $this->role->add($data);
            $permissions = $this->input->post('permissions');

            if ($role) {
                $role_id = $this->db->insert_id();
                $this->role->addPermissions($role_id,  $permissions);
            }

            redirect('dash/roles', 'refresh');
        }
    }

    public function edit(Int $id)
    {
        $this->updateView($id);
    }

    public function update(Int $id)
    {
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $permissions = $this->input->post('permissions');

        $role = $this->role->find($id);

        if ($role) {
            if ($permissions == null) {
                $this->role->deletePermissions($role->id);
            } else {
                $this->role->editPermissions($role->id, $permissions);
            }

            $this->role->edit([
                'id' => $role->id,
                'title' => $title,
                'description' => $description
            ]);

            redirect('dash/roles', 'refresh');
        }
    }

    public function delete(Int $id)
    {
        $this->role->delete($id);
        redirect('dash/roles', 'refresh');
    }

    private function createView()
    {
        $title = self::TAG;
        $permissions = $this->permission->all();
        $this->load->view('dash/role/create', compact('title', 'permissions'));
    }

    private function updateView($id)
    {
        $title = self::TAG;
        $role = $this->role->find($id);
        $permissions = $this->permission->all();
        $role_has_permission = $this->role->roleHasPermissions($id);
        $this->load->view('dash/role/edit', compact('title', 'role', 'permissions', 'role_has_permission'));
    }
}
