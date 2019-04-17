<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
		$this->load->view('auth/login');
    }

    public function login()
    {

    }

    private function validate()
    {

    }

    private function failedCallback()
    {

    }

}