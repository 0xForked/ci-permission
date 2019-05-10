<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionController extends CI_Controller {

    const TAG = 'permission';

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
        $permissions = $this->permission->all();
		$this->load->view('dash/permission/index', compact('permissions', 'title'));
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
                'description' => $this->input->post('description')
            ];
            $this->permission->add($data);
            redirect('dash/permissions', 'refresh');
        }
    }

    public function delete(Int $id)
    {
        $this->permission->delete($id);
        redirect('dash/permissions', 'refresh');
    }

    private function createView()
    {
        $title = self::TAG;
        $this->load->view('dash/permission/create', compact('title'));
    }
}
