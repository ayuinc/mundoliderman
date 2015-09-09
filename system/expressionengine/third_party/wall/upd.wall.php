<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wall_upd {
	
	public $version = "1.0";

	private $module_name = "Wall";
	private $EE;

	// Constructor
	function __construct()
	{
		$this->EE =& get_instance();
		$this->EE->load->dbforge();
	}

	/**
	* Install the module
	*
	* @return boolean TRUE
	*/
	public function install() 
	{
		$mod_data = array(
			"module_name" => $this->module_name,
			"module_version" => $this->version,
			"has_cp_backend" => "y",
			"has_publish_fields" => "n"
		);
		$this->EE->db->insert("modules", $mod_data);	

		if (!$this->EE->db->table_exists('tareo')) {
			$fields = array(
				'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'code' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
				'description' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE)
			);

			$this->EE->dbforge->add_field($fields);
			$this->EE->dbforge->add_key('id', TRUE);
			$this->EE->dbforge->create_table('tareo');

			$data = array(
				array(
					'code' => 'BAJA',
					'description' => 'Personal de Baja'
				),
				array(
					'code' => 'D',
					'description' => 'Diurno'
				),
				array(
					'code' => 'F',
					'description' => 'Falta'
				),
				array(
					'code' => 'N',
					'description' => 'Noche'
				),
				array(
					'code' => 'X',
					'description' => 'Descanso'
				)
			);

			$this->EE->db->insert_batch('tareo', $data);

			unset($fields);
			unset($data);
		}

		$fields = array(
			'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'post_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE), 
			'member_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE), 
			'like' => array('type' => 'char', 'constraint' => '1', 'null' => FALSE, 'default' => 'n'), 
			'like_date' => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE, 'default' => '0', 'null' => FALSE)
		);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('wall_like');

		unset($fields);

		$fields = array(
			'id' => array('type' => 'int', 'unsigned' => TRUE),
			'name' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE)
		);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('wall_status_category');

		$data = array(
			array(
				'id' => '0',
				'name' => 'Escoge una categoría'
			),
			array(
				'id' => '1',
				'name' => 'Quiero divertirme'
			),
			array(
				'id' => '2',
				'name' => 'Quiero dar mi testimonio'
			),
			array(
				'id' => '3',
				'name' => 'Tengo una solicitud'
			),
			array(
				'id' => '4',
				'name' => 'Tengo una consulta'
			),
			array(
				'id' => '5',
				'name' => 'Necesito ayuda'
			),
			array(
				'id' => '6',
				'name' => 'Otro'
			)
		);

		$this->EE->db->insert_batch('wall_status_category', $data);

		unset($fields);

		$fields = array(
			'id' => array(
				'type' => 'int',
				'constraint' => '10',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'member_id' => array(
				'type' => 'int',
				'constraint' => '10',
				'unsigned' => TRUE,
				'null' => FALSE
			),
			'category_id' => array(
				'type' => 'int',
				'constraint' => '10',
				'unsigned' => TRUE,
				'null' => FALSE
			),
			'content' => array(
				'type' => 'varchar',
				'constraint' => '512',
				'null' => FALSE
			),
			'image_path' => array(
				'type' => 'varchar',
				'constraint' => '512',
				'null' => TRUE
			),
			"active" => array(
				'type' => 'char',
				'constraint' => '1',
				'default' => 'y',
				'null' => FALSE
			),
			'status_date' => array(
				'type' => 'int',
				'constraint' => '10',
				'unsigned' => TRUE,
				'default' => '0',
				'null' => FALSE
			)
		);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('wall_status');

		unset($fields);

		$fields = array(
			'id' => array(
				'type' => 'int',
				'constraint' => '10',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'post_id' => array(
				'type' => 'int',
				'constraint' => '10',
				'unsigned' => TRUE,
				'null' => FALSE
			),
			'comment_member_id' => array(
				'type' => 'int',
				'constraint' => '10',
				'unsigned' => TRUE,
				'null' => FALSE
			),
			'comment' => array(
				'type' => 'varchar',
				'constraint' => '512',
				'null' => FALSE
			),
			'comment_date' => array(
				'type' => 'int',
				'constraint' => '10',
				'unsigned' => TRUE,
				'null' => FALSE
			)
		);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('wall_comment');

		unset($fields);
		
		$fields = array(
			'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'member_id' => array('type' => 'int', 'unsigned' => TRUE, 'constraint' => '10', 'null' => FALSE),
			'premium' => array('type' => 'char', 'constraint' => '1', 'null' => FALSE, 'default' => 'n'), 
			'prominent' => array('type' => 'char', 'constraint' => '1', 'null' => FALSE, 'default' => 'n'), 
			'achieve_date' => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE, 'default' => '0', 'null' => FALSE)
		);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('member_achievement');

		unset($data);

		$data = array(
			array(
				"class" => $this->module_name,
				"method" => "wall_post"
			),
			array(
				"class" => $this->module_name,
				"method" => "delete_post"	
			),
			array(
				"class" => $this->module_name,
				"method" => "comment_post"
			),
			array(
				"class" => $this->module_name,
				"method" => "like_post"
			),
			array(
				"class" => $this->module_name,
				"method" => "member_premium"
			),
			array(
				"class" => $this->module_name,
				"method" => "member_prominent"
			)
		);

		$this->EE->db->insert_batch("actions", $data);

		return TRUE;
	}

	/** 
	* Uninstall the module
	*
	* @return boolean TRUE
	*/
	public function uninstall()
	{
		$this->EE->db->select("module_id");
		$query = $this->EE->db->get_where("modules", 
			array( 
				"module_name" => $this->module_name 
			)
		);
		$this->EE->db->where("module_id", $query->row("module_id"));
		$this->EE->db->delete("module_member_groups");

		$this->EE->db->where("module_name", $this->module_name);
		$this->EE->db->delete("modules");

		$this->EE->db->where("class", $this->module_name);
		$this->EE->db->delete("actions");

		$this->EE->db->where("class", $this->module_name."_mcp");
		$this->EE->db->delete("actions");

		$this->EE->dbforge->drop_table("wall_like");
		$this->EE->dbforge->drop_table("wall_status_category");
		$this->EE->dbforge->drop_table("wall_status");
		$this->EE->dbforge->drop_table("wall_comment");
		$this->EE->dbforge->drop_table("member_achievement");
		
		return TRUE;
	}

	/**
	* Update the module
	*
	* @return boolean
	*/
	public function update($current = "")
	{
		if ($current == $this->version) {
			// No updates
			return FALSE;
		}

		return TRUE;
	}
}