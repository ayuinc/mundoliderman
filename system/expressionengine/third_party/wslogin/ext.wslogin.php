<?php

class Wslogin_ext {
	var $name = 'Web Service Login';
	var $version = '1.0';
	var $description = 'Web Service validation to login';
	var $settings_exist = 'n';
	var $docs_url = '';

	var $settings = array();

	var $host = "http://190.187.13.164";
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
		ee()->load->dbforge();
		$this->CI->load->library("curl");
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

		ee()->db->insert('extensions', $data);
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
	}

	function ws_login() 
	{
		$username = ee()->input->post("username");
		$password = ee()->input->post("password");
		$url = $this->host . "/WSIntranet/Autenticacion.svc/AutenticacionUsuario/$username/$password";
		$data = $this->CI->curl->get($url);
		$token = $data["TokenSeguridad"];
		if ($token == null) {
			// Retorna error
			// return ee()->functions->redirect(ee()->functions->fetch_site_index());
			return ee()->functions->redirect(ee()->functions->fetch_site_index());
		}
		else {
			// Almacena el token y debería guardar y/o actualizar la data del usuario en la base de datos con la del web service
			//var_dump(sha1($password));exit();
			ee()->load->library("auth");
			ee()->load->helper("url_helper");
			/*$query = ee()->db->select('member_id, group_id, username, screen_name')
					 ->from('members')
					 ->where(array("username" => $username))
					 ->get();*/
			$query = ee()->db->where("username", $username)
					 ->get("members");

			$this->getCustomMemberFields();

			if ($query->num_rows() > 0) {
				$member_id = $query->row("member_id");
				$member_salt = $query->row("salt");
				$member_password = $query->row("password");
				$hash = ee()->auth->hash_password($password, $member_salt, strlen($member_salt));
				//$hash["salt"] = htmlspecialchars($hash["salt"], ENT_QUOTES);
				//$hash["password"] = htmlspecialchars($hash["password"], ENT_QUOTES);
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
			} else {
				ee()->load->helper('security');
				$hash = ee()->auth->hash_password($password);

				// Assign the base query data
				$member_data = array(
					'username'		=> $username,
					'password'		=> $hash["password"],
					'salt'			=> $hash["salt"],
					'ip_address'	=> ee()->input->ip_address(),
					'unique_id'		=> ee()->functions->random('encrypt'),
					'join_date'		=> ee()->localize->now,
					'email'			=> $data["Correo"],
					'screen_name'	=> $data["Nombres"],
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

				// Set member group

				if (ee()->config->item('req_mbr_activation') == 'manual' OR
					ee()->config->item('req_mbr_activation') == 'email')
				{
					$member_data['group_id'] = ee()->config->item('default_member_group');
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
	        $this->getMemberFieldId('zona')  => $data["Zona"]
	    );
	}

}