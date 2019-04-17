
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// rest password berdasarkan link dari email

class Reset extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // ini dpe view
    }

    public function reset()
    {
        // ini dpe fungsi, abis dia reset password dia mo kirim konfirmasi email
    }

}