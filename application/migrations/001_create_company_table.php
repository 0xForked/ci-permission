<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Company_Table extends CI_Migration {

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
        $this->dbforge->create_table('companies');

        $this->seeds();
    }

    public function down()
    {
        $this->dbforge->drop_table('companies');
    }

    private function seeds()
    {
		$data = [
			[
				'title'         => 'PT. Kapal Sejahtera',
				'description'   => 'PT. Kapal Sejahtera adalah lorpe ipsum dolor do asmet'
			],
        ];

		$this->db->insert_batch('companies', $data);
    }

}