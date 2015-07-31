<?php

class Wslogin_ext {
	var $name = 'Web Service Login';
	var $version = '1.0';
	var $description = 'Web Service validation to login';
	var $settings_exist = 'n';
	var $docs_url = '';

	var $settings = array();

	var $host = "http://190.187.13.164";

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
		}
		else {
			// Almacena el token y debería guardar y/o actualizar la data del usuario en la base de datos con la del web service
			//var_dump(sha1($password));exit();
			ee()->load->library("auth");
			/*$query = ee()->db->select('member_id, group_id, username, screen_name')
					 ->from('members')
					 ->where(array("username" => $username))
					 ->get();*/
			$query = ee()->db->where("username", $username)
					 ->get("members");
			if ($query->num_rows() > 0) {
				$member_id = $query->row("member_id");
				$member_salt = $query->row("salt");
				$member_password = $query->row("password");
				$hash = ee()->auth->hash_password($password, $member_salt, strlen($member_salt));
				if ($member_salt !== $hash["salt"] || $member_password !== $hash["password"]) {
					ee()->db->update('members',
						array(
							'password' => $hash["password"],
							'salt' => $hash["salt"]
						),
						array(
							'member_id' => $member_id
						)
					);
				}
				//ee()->db->update('members')
			}
		}
	}
}