
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// rest password berdasarkan link dari email

class Reset extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if($this->session->userdata('loginStatus')){
            redirect('dash/home');
        }
    }

    public function index()
    {
        // ini dpe view
    }

    private function validate()
    {
        // ini dpe fungsi, abis dia reset password dia mo kirim konfirmasi email
    }

}
