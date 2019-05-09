<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CompanyController extends CI_Controller {

    const TAG = 'company';

    public function __construct()
    {
       parent::__construct();

        // check user isLoggedIn
        $this->auth->routeAccess();

        // check if user is ...
        // if not redirect to user role page
        if (!has_role(['root', 'vendor'])) {
            show_404();
        }
    }

	public function index()
	{
        $title = self::TAG;
        $companies = $this->company->all();
		$this->load->view('dash/company/index', compact('title', 'companies'));
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

            $company = $this->company->add($data);

            redirect('dash/companies', 'refresh');
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

        $company = $this->company->find($id);

        if ($company) {
            $this->company->edit([
                'id' => $company->id,
                'title' => $title,
                'description' => $description
            ]);

            redirect('dash/companies', 'refresh');
        }
    }

    public function delete(Int $id)
    {
        $this->company->delete($id);
        redirect('dash/companies', 'refresh');
    }

    private function createView()
    {
        $title = self::TAG;
        $this->load->view('dash/company/create', compact('title'));
    }

    private function updateView($id)
    {
        $title = self::TAG;
        $company = $this->company->find($id);
        $this->load->view('dash/company/edit', compact('title', 'company'));
    }
}
