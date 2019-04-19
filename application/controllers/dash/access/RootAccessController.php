<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RootAccessController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // check user isLoggedIn 
        $this->auth->routeAccess();

        // check if user is vendor 
        // if not redirect to user role page
        if (!hasRole('root')) {
            show_404();  
        }
    }

	public function index()
	{
        $title = "access";
		$this->load->view('dash/access/root', compact('title'));
	}
}