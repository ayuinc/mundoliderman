<?php

class Lidernet_ext {
	var $name = 'Web Service Lidernet';
	var $version = '1.0';
	var $description = 'Web Service to register lidernet';
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
		$this->EE =& get_instance();
		$this->EE->load->dbforge();
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
			'method' => 'register_lidernet',
			'hook' => 'freeform_module_insert_begin',
			'settings' => serialize($this->settings),
			'priority' => 1,
			'version' => $this->version,
			'enabled' => 'y'
		);

		$this->EE->db->insert('extensions', $data);

		unset($data);
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

	function register_lidernet($inputs, $entry_id, $form_id, $obj) 
	{
		if (REQ == 'CP') return $inputs;

		$from = ee()->input->post("from");

		if (is_null($from) || $from != 'lidernet') return $inputs;

		$month = ee()->input->post("month");
		$year = ee()->input->post("year");
		$member_id = ee()->session->userdata('member_id');

		if ($member_id == null || $member_id <= 0) {
			ee()->functions->redirect("wall");
			return;
		}

		$dni_field_name = $this->getMemberFieldId("dni");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$dni_field_name, $token_field_name")
				         ->get('exp_member_data');

		$dni = $query->row($dni_field_name);
		$token = $query->row($token_field_name);

		$url = $this->host . "/WSIntranet/LiderNet.svc/ActualizarEstado/$year/$month/$dni/$token";

		$data = $this->EE->curl->get($url);
		return $inputs;
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
		if (!isset($this->custom_fields)) {
			$this->getCustomMemberFields();
		}
		foreach ($this->custom_fields as $key => $value) {
			if ($field_name == $value["m_field_name"]) {
				$field_id = $value["m_field_id"];
				break;
			}
		}
		return 'm_field_id_' . $field_id;
	}
}
