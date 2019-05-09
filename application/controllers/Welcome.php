<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
        $data = [
            'title' => 'home',
            'subtitle' => 'welcome'
        ];
		$this->load->view('welcome', $data);
    }
}
