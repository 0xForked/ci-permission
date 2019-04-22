<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_User_Login_Attempt_Table extends CI_Migration {

    public function up()
    {
		$this->dbforge->add_field([
			'id' => [
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'ip_address' => [
				'type'       => 'VARCHAR',
				'constraint' => '45'
			],
			'login' => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null'       => TRUE
			],
			'time' => [
				'type'       => 'INT',
				'constraint' => '11',
				'unsigned'   => TRUE,
				'null'       => TRUE
			]
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('user_login_attempts');
    }

    public function down()
    {
        $this->dbforge->drop_table('user_login_attempts');
    }
}