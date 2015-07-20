<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Backform_upd {

	public $version = '1.0';

	private $module_name = 'Backform';
	private $EE;

	// Constructor
	public function __construct() 
	{
		$this->EE =& get_instance();
	}

	public function install() 
	{
		$mod_data = array(
			'module_name' = $this->module_name,
			'module_version' = $this->version,
			'has_cp_backend' => 'n',
			'has_publish_fields' => 'n'
		);

		$this->EE->db->insert('modules', $mod_data);

		$data = array(
			'class' => $this->module_name,
			'method' => 'default'
		);

		$this->EE->db->insert('actions', $data);

		return TRUE;
	}

	public function uninstall() 
	{
		$this->EE->db->where('module_name', $this->module_name);
		$this->EE->db->delete('modules');

		$this->EE->db->where('class', $this->module_name);
		$this->EE->db->delete('actions');

		return TRUE;
	}

	public function update($current = '')
	{
		if ($current == $this->version) {
			return FALSE;
		}

		return TRUE;
	}

}