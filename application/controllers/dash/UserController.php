<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

    const TAG = 'user';
    const DEFAULT_PASSWORD = 'secret';
    const DEFAULT_PHONE = '+6282200000000';


    public function __construct()
    {
       parent::__construct();
       $this->load->library('Mailer');

        // check user so
        $this->auth->routeAccess();

        // check if user is ...
        // if not redirect to user role page
        if (!has_role(['root', 'vendor', 'admin'])) {
            show_404();
        }

    }

	public function index()
	{
        $title = self::TAG;
        $company = $this->auth->company();

        if (has_role('root')) {
            $user_data = $this->user->all();
        }

        if (has_role('vendor')) {
            $user_data = $this->user->usersWithRolesAndHasCompany([
                            VENDOR_ROLE,
                            ADMIN_ROLE,
                            STAFF_ROLE
                        ]);
        }

        if (has_role('admin')) {
            $user_data = $this->user->usersWithRolesAndHasCompany([
                            ADMIN_ROLE,
                            STAFF_ROLE
                        ], $company);
        }

        $users = [];
        foreach ($user_data as $user) {
            $role = $this->user->userHasRoleDetails($user->id);
            $company = $this->company->find($user->company_id);
            $users[] = (object) [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'active' => $user->active,
                'role' => $role[0],
                'company' => $company
            ];
        }
		$this->load->view('dash/user/index', compact('title', 'users'));
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
                'phone' => self::DEFAULT_PHONE,
                'created_on' => time(),
                'company_id' => ((int)$this->input->post('company') === 0) ? null : (int)$this->input->post('company')
            ];

            $user = $this->user->add($data);
            $role = $this->input->post('role');

            if ($user) {
                $user_id = $this->db->insert_id();
                $this->user->addRoles($user_id,  $role);

                if ($this->input->post('status') == 0) {
                    $message = "Selamat datang di CI-Permission untuk mengaktifkan akun silahkan klik link berikut: Link!";
                    $this->sendMail($this->input->post('email'), $message);
                }
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
                'active' => $active,
                'company_id' => ((int)$this->input->post('company') === 0) ? null : (int)$this->input->post('company')
            ]);

            if ($active == 0) {
                $message = "Akun anda di nonaktifkan!";
                $this->sendMail($user->email, $message);
            }

            redirect('dash/users', 'refresh');
        }
    }

    public function delete(Int $id)
    {
        $this->user->delete($id);
        redirect('dash/users', 'refresh');
    }

    public function deactive($id)
    {
        if (!isset($id)) {
            // $this->set_error('deactivate_unsuccessful');
			return FALSE;
        } else if ($this->uid() == $id) {
            // $this->set_error('deactivate_current_user_unsuccessful');
			return FALSE;
        }

        return $this->user->deactivate($id);
    }

    private function createView()
    {
        $title = self::TAG;

        if (has_role('root')) {
            $roles = $this->role->all();
            $companies = $this->company->all();
        }

        if (has_role('vendor')) {
            $roles = $this->role->whereNot([ROOT_ROLE]);
            $companies = $this->company->all();
        }

        if (has_role('admin')) {
            $company = $this->auth->company();
            $roles = $this->role->whereNot([ROOT_ROLE, VENDOR_ROLE, MEMBER_ROLE]);
            $companies = $this->company->findAll($company);
        }

        $this->load->view('dash/user/create', compact('title', 'roles', 'companies'));
    }

    private function updateView($id)
    {
        $title = self::TAG;

        if (has_role('root')) {
            $roles = $this->role->all();
            $companies = $this->company->all();
        }

        if (has_role('vendor')) {
            $roles = $this->role->whereNot([ROOT_ROLE]);
            $companies = $this->company->all();
        }

        if (has_role('admin')) {
            $company = $this->auth->company();
            $roles = $this->role->whereNot([ROOT_ROLE, VENDOR_ROLE, MEMBER_ROLE]);
            $companies = $this->company->findAll($company);
        }

        $user = $this->user->find($id);
        $user_has_role = $this->user->userHasRoles($id);

        $this->load->view('dash/user/edit', compact('title', 'user', 'roles', 'companies', 'user_has_role'));
    }

    private function sendMail($to, $message)
    {
        $data = [
            'to' => $to,
            'subject' => 'New User',
            'message' => $message
        ];

        $this->mailer->send($data);
    }
}
