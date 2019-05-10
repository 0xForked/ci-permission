<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_User_Detail_Table extends CI_Migration {

    public function up()
    {
		$this->dbforge->add_field([
            'user_id' => [
				'type'              => 'INT',
				'constraint'        => 5,
				'unsigned'          => TRUE,
				'null'       		=> TRUE
            ],
            'first_name' => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
            ],
            'last_name' => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
			]
		]);
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(id)");
        $this->dbforge->create_table('users_detail');

        $this->seeds();

    }

    public function down()
    {
        $this->dbforge->drop_table('users_detail');
    }

    private function seeds()
    {
		$data = [
			[
                'user_id'       => 1,
                'first_name'    => 'Root',
                'last_name'     => 'User'
			],
			[
                'user_id'       => 2,
                'first_name'    => 'Vendor',
                'last_name'     => 'User',
			],
			[
                'user_id'       => 3,
                'first_name'    => 'Admin',
                'last_name'     => 'User',
			],
			[
                'user_id'       => 4,
                'first_name'    => 'Staff',
                'last_name'     => 'User',
			],
        ];

		$this->db->insert_batch('users_detail', $data);
    }
}