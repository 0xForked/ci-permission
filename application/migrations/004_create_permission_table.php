<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Permission_Table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field([
			'id' => [
				'type'              => 'INT',
				'constraint'        => 5,
                'unsigned'          => TRUE,
                'auto_increment'    => TRUE
            ],
			'title' => [
				'type'       => 'VARCHAR',
				'constraint' => '20',
			],
			'description' => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
			]
		]);
		$this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('permissions');

        $this->seeds();
    }

    public function down()
    {
        $this->dbforge->drop_table('permissions');
    }

    private function seeds()
    {
		$data = [
			[
				'title'        => 'create-user',
				'description' => 'create new user permission '
			],
			[
				'title'        => 'update-user',
				'description' => 'update user account permission'
			],
			[
				'title'        => 'delete-user',
				'description' => 'delete user account permission'
            ],
			[
				'title'        => 'list-user',
				'description' => 'list all users permission'
            ],
			[
				'title'        => 'show-user',
				'description' => 'show user details'
            ],
			[
				'title'        => 'create-role',
				'description' => 'create new role permission '
			],
			[
				'title'        => 'update-role',
				'description' => 'update role'
			],
			[
				'title'        => 'delete-role',
				'description' => 'delete role'
            ],
			[
				'title'        => 'list-role',
				'description' => 'list all role'
            ],
			[
				'title'        => 'show-role',
				'description' => 'show role details'
            ],
			[
				'title'        => 'create-permission',
				'description' => 'create new permission '
			],
			[
				'title'        => 'delete-permission',
				'description' => 'delete permission'
            ],
			[
				'title'        => 'list-permission',
				'description' => 'list all permission'
            ],
        ];

		$this->db->insert_batch('permissions', $data);
    }

}