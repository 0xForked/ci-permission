<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Role_Table extends CI_Migration {

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
        $this->dbforge->create_table('roles');

        $this->seeds();
    }

    public function down()
    {
        $this->dbforge->drop_table('roles');
    }

    private function seeds()
    {
		$data = [
			[
				'title'        => 'root',
				'description' => 'Super User Level Account - {hak akses super} '
			],
			[
				'title'        => 'vendor',
				'description' => 'Middle Level Account - {untuk pengelola}'
			],
			[
				'title'        => 'admin',
				'description' => 'Admin Level Account - {untuk pemilik/pengusahan jasa pelayaran}'
            ],
			[
				'title'        => 'staff',
				'description' => 'Staff Level Account - {untuk karyawan dari pemilik/pengusaha jasa pelayaran}'
            ],
			[
				'title'        => 'member',
				'description' => 'Member Level Account - {untuk user sebagai pengguna jasa}'
			]
        ];

		$this->db->insert_batch('roles', $data);
    }

}