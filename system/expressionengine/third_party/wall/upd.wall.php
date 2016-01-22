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
				'description' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
				'bg_color' => array('type' => 'varchar', 'constraint' => '250')
			);

			$this->EE->dbforge->add_field($fields);
			$this->EE->dbforge->add_key('id', TRUE);
			$this->EE->dbforge->create_table('tareo');

			$data = array(
				array(
					'code' => 'D',
					'description' => 'Diurno',
					'bg_color' => 'bg-diurno'
				),
				array(
					'code' => 'T',
					'description' => 'Tarde',
					'bg_color' => 'bg-tarde'
				),
				array(
					'code' => 'N',
					'description' => 'Noche',
					'bg_color' => 'bg-nocturno'
				),
				array(
					'code' => 'G',
					'description' => 'Guardian',
					'bg_color' => 'bg-guardian'
				),
				array(
					'code' => 'F',
					'description' => 'Falto',
					'bg_color' => 'bg-falto2'
				),
				array(
					'code' => 'X',
					'description' => 'Descanso Laboral',
					'bg_color' => 'bg-descanso3'
				),
				array(
					'code' => 'FF',
					'description' => 'Falto al Servicio',
					'bg_color' => 'bg-falto'
				),
				array(
					'code' => 'BAJA',
					'description' => 'Personal de Baja',
					'bg_color' => 'bg-baja'
				),
				array(
					'code' => 'PN',
					'description' => 'Permiso no Pagado',
					'bg_color' => 'bg-pagado'
				),
				array(
					'code' => 'S',
					'description' => 'Suspensión',
					'bg_color' => 'bg-suspension'
				),
				array(
					'code' => 'PT',
					'description' => 'Traslado a Pulpo',
					'bg_color' => 'bg-traslado'
				),
				array(
					'code' => 'LSGOC',
					'description' => 'Licencia sin Goce',
					'bg_color' => 'bg-lsgoc'
				),
				array(
					'code' => 'ASERV',
					'description' => 'Abandono de Servicio',
					'bg_color' => 'bg-abandono'
				),
				array(
					'code' => 'ITTSS',
					'description' => 'Incapacidad Temporal Sin Subsidio',
					'bg_color' => 'bg-incapacidad2'
				),
				array(
					'code' => 'SDESA',
					'description' => 'Servicio Desactivado',
					'bg_color' => 'bg-servicio4'
				),
				array(
					'code' => 'SF',
					'description' => 'Servicio Especial Extraordinario',
					'bg_color' => 'bg-servicio'
				),
				array(
					'code' => 'SNCUB',
					'description' => 'Servicio No Cubierto',
					'bg_color' => 'bg-servicio3'
				),
				array(
					'code' => 'SP',
					'description' => 'Servicio Permanente',
					'bg_color' => 'bg-servicio2'
				),
				array(
					'code' => 'SUBEN',
					'description' => 'Subsidio Enfermedad',
					'bg_color' => 'bg-subsidio'
				),
				array(
					'code' => 'SUBMA',
					'description' => 'Subsidio Maternidad',
					'bg_color' => 'bg-subsidio2'
				),
				array(
					'code' => 'DM',
					'description' => 'Descanso Médico',
					'bg_color' => 'bg-descanso2'
				),
				array(
					'code' => 'PP',
					'description' => 'Permiso Pagado',
					'bg_color' => 'bg-pagado2'
				),
				array(
					'code' => 'VC',
					'description' => 'Vacaciones',
					'bg_color' => 'bg-vacaciones'
				),
				array(
					'code' => 'VT',
					'description' => 'Venta de Vacaciones',
					'bg_color' => 'bg-venta'
				),
				array(
					'code' => 'LCGOC',
					'description' => 'Licencia con Goce',
					'bg_color' => 'bg-lcgoc'
				),
				array(
					'code' => 'DESC',
					'description' => 'Descanso',
					'bg_color' => 'bg-descanso'
				),
				array(
					'code' => 'IP',
					'description' => 'Incapacidad Permanente',
					'bg_color' => 'bg-incapacidad'
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
			'solved' => array(
				'type' => 'char',
				'constraint' => '1',
				'default' => 'n',
				'null' => FALSE
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
			'active' => array(
				'type' => 'char',
				'constraint' => '1',
				'default' => 'y',
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
				"method" => "delete_comment"
			),
			array(
				"class" => $this->module_name,
				"method" => "like_post"
			),
			array(
				"class" => $this->module_name,
				"method" => "solve_post"
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