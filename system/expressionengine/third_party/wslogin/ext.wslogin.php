<?php

class Wslogin_ext {
	var $name = 'Web Service Login';
	var $version = '1.0';
	var $description = 'Web Service validation to login';
	var $settings_exist = 'n';
	var $docs_url = '';

	var $settings = array();

	var $host;
	var $custom_fields;

	var $CI = '';

	/**
	 * Constructor 
	 *
	 * @param mixed Settings array or empty string if none exist
	 */	
	function __construct($settings='') 
	{
		$this->settings = $settings;
		$this->CI =& get_instance();
		$this->EE =& get_instance();
		$this->EE->load->dbforge();
		$this->CI->load->library("curl");
		$this->host = $this->EE->config->item('webservice_url');
	}
	// END

	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @return void
	 */
	function activate_extension()
	{		
		$data = array(
			'class' => __CLASS__,
			'method' => 'ws_login',
			'hook' => 'member_member_login_start',
			'settings' => serialize($this->settings),
			'priority' => 1,
			'version' => $this->version,
			'enabled' => 'y'
		);

		$this->EE->db->insert('extensions', $data);

		unset($data);

		$fields = array(
				'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'code' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
				'description' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
				'group_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE)
			);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('profiles');

		$data = array(
			array(
				'code' => '001 01',
				'description' => 'Tipo Persona - Mundo Liderman Liderman',
				'group_id' => '7'
			),
			array(
				'code' => '001 02',
				'description' => 'Tipo Persona - Mundo Liderman Resguardo',
				'group_id' => '8'
			),
			array(
				'code' => '001 03',
				'description' => 'Tipo Persona - Mundo Liderman Chateadoras',
				'group_id' => '6'
			),
			array(
				'code' => '001 04',
				'description' => 'Tipo Persona - Mundo Liderman Sub Gerencia Comunicaciones',
				'group_id' => '9'
			),
			array(
				'code' => '001 05',
				'description' => 'Tipo Persona - Mundo Liderman Ama Crece',
				'group_id' => '10'
			),
			array(
				'code' => '001 06',
				'description' => 'Tipo Persona - Mundo Liderman Sub Gerencia GTH',
				'group_id' => '11'
			),
			array(
				'code' => '001 07',
				'description' => 'Tipo Persona - Mundo Liderman Sub Gerencia de Operaciones',
				'group_id' => '12'
			),
			array(
				'code' => '001 08',
				'description' => 'Tipo Persona - Mundo Liderman Gerencia de Operaciones',
				'group_id' => '13'
			),
			array(
				'code' => '001 09',
				'description' => 'Tipo Persona - Mundo Liderman Lideres Zonales Lima',
				'group_id' => '14'
			),
			array(
				'code' => '001 10',
				'description' => 'Tipo Persona - Mundo Liderman Lideres Zonales Provincias',
				'group_id' => '15'
			),
			array(
				'code' => '001 11',
				'description' => 'Tipo Persona - Mundo Liderman Sistemas',
				'group_id' => '16'
			),
			array(
				'code' => '001 12',
				'description' => 'Tipo Persona - Mundo Liderman Liderman Premium',
				'group_id' => '17'
			),
			array(
				'code' => '001 13',
				'description' => 'Tipo Persona - Mundo Liderman Planillas',
				'group_id' => '18'
			),
			array(
				'code' => '001 14',
				'description' => 'Tipo Persona - Mundo Liderman Centro de Control',
				'group_id' => '19'
			),
			array(
				'code' => '001 15',
				'description' => 'Tipo Persona - Mundo Liderman Central de Agentes',
				'group_id' => '20'
			),
			array(
				'code' => '001 16',
				'description' => 'Tipo Persona - Mundo Liderman Liderman Coordinador',
				'group_id' => '21'
			),
			array(
				'code' => '001 17',
				'description' => 'Tipo Persona - Mundo Liderman Clientes',
				'group_id' => '22'
			),
			array(
				'code' => '001 18',
				'description' => 'Tipo Persona - Mundo Liderman Equipo Administración, Contabilidad, Finanzas, Facturación y Legal',
				'group_id' => '23'
			),
			array(
				'code' => '001 19',
				'description' => 'Tipo Persona - Mundo Liderman Equipo Bienestar Social',
				'group_id' => '24'
			),
			array(
				'code' => '001 20',
				'description' => 'Tipo Persona - Mundo Liderman Gerencia General Liderman',
				'group_id' => '25'
			),
			array(
				'code' => '001 21',
				'description' => 'Tipo Persona - Mundo Liderman Gerencia Comercial',
				'group_id' => '26'
			),
			array(
				'code' => '001 22',
				'description' => 'Tipo Persona - Mundo Liderman Asistente Lideres Zonales',
				'group_id' => '27'
			),
			array(
				'code' => '001 23',
				'description' => 'Tipo Persona - Mundo Liderman Logística',
				'group_id' => '28'
			),
			array(
				'code' => '001 24',
				'description' => 'Tipo Persona - Mundo Liderman Guardianes',
				'group_id' => '29'
			),
			array(
				'code' => '001 25',
				'description' => 'Tipo Persona - Mundo Liderman Equipo Operaciones',
				'group_id' => '30'
			),
			array(
				'code' => '001 26',
				'description' => 'Tipo Persona - Mundo Liderman Equipo GTH',
				'group_id' => '31'
			),
			array(
				'code' => '001 27',
				'description' => 'Tipo Persona - Mundo Liderman Equipo Comunicaciones',
				'group_id' => '32'
			),
			array(
				'code' => '001 28',
				'description' => 'Tipo Persona - Mundo Liderman Gerencia de Administración y Finanzas',
				'group_id' => '33'
			),
			array(
				'code' => '001 29',
				'description' => 'Tipo Persona - Mundo Liderman Otros (chofer javier, recepcionistas, limpieza, guardianes)',
				'group_id' => '34'
			),
			array(
				'code' => '001 30',
				'description' => 'Tipo Persona - Mundo Liderman Clave 03',
				'group_id' => '35'
			),
			array(
				'code' => '001 31',
				'description' => 'Tipo Persona - Mundo Liderman Supervisores',
				'group_id' => '36'
			),
			array(
				'code' => '001 32',
				'description' => 'Tipo Persona - Mundo Liderman Recibo por honorarios',
				'group_id' => '37'
			),
			array(
				'code' => '001 00',
				'description' => 'Tipo Persona - Mundo Liderman Guardianes de la Cultura Liderman',
				'group_id' => '38'
			)
		);

		$this->EE->db->insert_batch('profiles', $data);
	}

	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension page is visited
	 *
	 * @return mixed void on update/false if none
	 */
	function update_extension($current='')
	{
		if ($current == '' OR $current == $this->version) 
		{
			return FALSE;
		}

		if ($current < '1.0') 
		{
			# Update to version 1.0
		}

		ee()->db->where('class', __CLASS__);
		ee()->db->update(
			'extensions',
			array('version' => $this->version)
		);
	}

	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');

		$this->EE->dbforge->drop_table("profiles");
	}

	function ws_login() 
	{
		$user_lidermania = ee()->config->item("user_lidermania");

		$username = ee()->input->post("username");
		$password = ee()->input->post("password");

		if ($username == $user_lidermania) {
			return;
		}

		$password = (isset($password) && (strlen($password) > 10)) ? substr($password, 0, 10) : $password;
		$url = $this->host . "/WSIntranet/Autenticacion.svc/AutenticacionUsuario/$username/$password";
		$data = $this->CI->curl->get($url);
		$token = $data["TokenSeguridad"];
		$employee_category = $this->cleanCategoryCode($data["CategoriaEmpleado"]);
		if ($token == null) {
			// Retorna error
			return ee()->output->show_user_error('submission', 'Usuario y/o contraseña incorrecta');
		}
		else {
			// Almacena el token y debería guardar y/o actualizar la data del usuario en la base de datos con la del web service
			ee()->load->library("auth");
			ee()->load->helper("url_helper");
			$query = ee()->db->where("username", $username)
					 ->get("members");

			var_dump($query);
			die();

			$this->getCustomMemberFields();

			if ($query->num_rows() > 0) {
				$member_id = $query->row("member_id");
				$member_salt = $query->row("salt");
				$member_password = $query->row("password");
				$hash = ee()->auth->hash_password($password, $member_salt, strlen($member_salt));
				if ($member_password != $hash["password"]) {
					ee()->db->update('members',
						array(
							'password' => $hash["password"],
							'salt' => $hash["salt"],
							'email' => $data["Correo"]
						),
						array(
							'member_id' => $member_id
						)
					);
				}
				ee()->db->update(
				    'member_data',
				    $this->setArrayData($data, $token),
				    array(
				        'member_id' => $member_id
				    )
				);

				$profiles = ee()->db->query("SELECT group_id from exp_profiles WHERE '$employee_category' = (SELECT REPLACE(`code`, ' ', ''))");

				ee()->db->update(
					'members',
					array(
						'group_id' => $profiles->row("group_id")
					),
					array(
						'member_id' => $member_id
					)
				);
			} else {
				ee()->load->helper('security');
				$hash = ee()->auth->hash_password($password);
				$space_position = strpos(strval($data["Nombres"]), ' ', 1);
				// Assign the base query data
				$member_data = array(
					'username'		=> $username,
					'password'		=> $hash["password"],
					'salt'			=> $hash["salt"],
					'ip_address'	=> ee()->input->ip_address(),
					'unique_id'		=> ee()->functions->random('encrypt'),
					'join_date'		=> ee()->localize->now,
					'email'			=> $data["Correo"],
					'screen_name'	=> ($space_position > 0) ? substr(strval($data["Nombres"]), 0, $space_position) : strval($data["Nombres"]),
					'url'			=> prep_url(ee()->input->post('url')),
					'location'		=> ee()->input->post('location'),

					// overridden below if used as optional fields
					'language'		=> (ee()->config->item('deft_lang')) ?
											ee()->config->item('deft_lang') : 'english',
					'date_format'	=> ee()->config->item('date_format') ?
							 				ee()->config->item('date_format') : '%n/%j/%y',
					'time_format'	=> ee()->config->item('time_format') ?
											ee()->config->item('time_format') : '12',
					'include_seconds' => ee()->config->item('include_seconds') ?
											ee()->config->item('include_seconds') : 'n',
					'timezone'		=> ee()->config->item('default_site_timezone')
				);

				$profiles = ee()->db->query("SELECT group_id from exp_profiles WHERE '$employee_category' = (SELECT REPLACE(`code`, ' ', ''))");

				// Set member group

				if (ee()->config->item('req_mbr_activation') == 'manual' OR
					ee()->config->item('req_mbr_activation') == 'email')
				{
					$group_id = $profiles->row("group_id");
					$member_data['group_id'] = isset($group_id) ? $group_id : ee()->config->item('default_member_group');
				}

				// Optional Fields

				$optional = array(
					'bio'				=> 'bio',
					'language'			=> 'deft_lang',
					'timezone'			=> 'server_timezone',
					'date_format'		=> 'date_format',
					'time_format'		=> 'time_format',
					'include_seconds'	=> 'include_seconds'
				);

				// Insert basic member data
				ee()->db->query(ee()->db->insert_string('exp_members', $member_data));

				$member_id = ee()->db->insert_id();

				$data = $this->setArrayData($data, $token);
				$data["member_id"] = $member_id;

				ee()->db->query(ee()->db->insert_string('exp_member_data', $data));
			}
		}
	}

	private function getCustomMemberFields()
	{
		$member_fields = ee()->db->where('m_field_reg', 'y')
						 ->get("member_fields");
		$this->custom_fields = $member_fields->result_array();
	}

	private function getMemberFieldId($field_name)
	{
		$field_id = 0;
		foreach ($this->custom_fields as $key => $value) {
			if ($field_name == $value["m_field_name"]) {
				$field_id = $value["m_field_id"];
				break;
			}
		}
		return 'm_field_id_' . $field_id;
	}

	private function setArrayData($data, $token)
	{
		return array(
	        $this->getMemberFieldId('apellidos')  => $data["Apellido"],
	        $this->getMemberFieldId('cargo')  => $data["Cargo"],
	        $this->getMemberFieldId('empresa-empleadora')  => $data["Cliente"],
	        $this->getMemberFieldId('codigo-liderman')  => $data["CodigoLiderman"],
	        $this->getMemberFieldId('correo-destinatario')  => $data["CorreoDestinatario"],
	        $this->getMemberFieldId('dni')  => $data["DNI"],
	        $this->getMemberFieldId('edad')  => $data["Edad"],
	        $this->getMemberFieldId('lider-zonal')  => $data["LiderZonal"],
	        $this->getMemberFieldId('nombres')  => $data["Nombres"],
	        $this->getMemberFieldId('periodo-planilla')  => $data["PeriodoPlanilla"],
	        $this->getMemberFieldId('sexo')  => $data["Sexo"],
	        $this->getMemberFieldId('tipo-usuario')  => $data["TipoUsuario"],
	        $this->getMemberFieldId('token')  => $token,
	        $this->getMemberFieldId('unidad')  => $data["Unidad"],
	        $this->getMemberFieldId('zona')  => $data["Zona"],
	        $this->getMemberFieldId('email-perfil') => $data["Correo"]
	    );
	}

	private function cleanCategoryCode($code) {
		return str_replace(" ", "", $code);
	}

}
