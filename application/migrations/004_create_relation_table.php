<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Relation_Table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field([
			'role_id' => [
				'type'              => 'INT',
				'constraint'        => 5,
                'unsigned'          => TRUE
            ],
			'permission_id' => [
				'type'              => 'INT',
				'constraint'        => 5,
                'unsigned'          => TRUE
            ]
		]);
        $this->dbforge->add_field("CONSTRAINT FOREIGN KEY (role_id) REFERENCES roles(id)");
        $this->dbforge->add_field("CONSTRAINT FOREIGN KEY (permission_id) REFERENCES permissions(id)");
        $this->dbforge->create_table('role_has_permission');

        $this->dbforge->add_field([
			'user_id' => [
				'type'              => 'INT',
				'constraint'        => 5,
                'unsigned'          => TRUE
            ],
			'role_id' => [
				'type'              => 'INT',
				'constraint'        => 5,
                'unsigned'          => TRUE
            ]
		]);
        $this->dbforge->add_field("CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(id)");
        $this->dbforge->add_field("CONSTRAINT FOREIGN KEY (role_id) REFERENCES roles(id)");
        $this->dbforge->create_table('user_has_role');

    }

    public function down()
    {
        $this->dbforge->drop_table('role_has_permission');
        $this->dbforge->drop_table('user_has_role');

    }


}