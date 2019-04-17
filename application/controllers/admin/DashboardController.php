<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->auth->routeAccess();
    }

	public function index()
	{
        $title = "dashboard";
		$this->load->view('admin/dashboard', compact('title'));
	}
}
