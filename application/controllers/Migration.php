<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends CI_Controller
{
    public function __construct()
    {
		parent::__construct();
		$this->input->is_cli_request() or exit("Execute via command line: php index.php migrate");
        $this->load->library('migration');
    }

    public function index()
    {
        if ($this->migration->latest()) {
            log_message('error', 'Migration Success.');
            echo "Migration Success";
        } else {
            log_message('error', $this->migration->error_string());
            echo $this->migration->error_string();
        }
    }

    public function rollback(Int $version)
    {
        if ($this->migration->version($version)) {
            log_message('error', 'Rollback Success.');
            echo "Migration Success";
        } else {
            log_message('error', $this->migration->error_string());
            echo $this->migration->error_string();
        }
    }

}
