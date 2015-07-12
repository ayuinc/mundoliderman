<?php

class Update_status_ext
{

	var $name = 'Friends Update Status';
	var $version = '1.0';
	var $description = 'Save category and image on status';
	var $settings_exist = 'n';
	var $docs_url = '';

	var $settings = array();

	/**
	 * Constructor 
	 *
	 * @param mixed Settings array or empty string if none exist
	 */	
	function __construct($settings='') 
	{
		$this->settings = $settings;
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
		ee()->load->dbforge();
		
		$fields = array(
			'status_id' => array('type' => 'int', 'unsigned' => TRUE),
			'category' => array('type' => 'int', 'unsigned' => TRUE),
			'image' => array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE)
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('status_id', TRUE);

		ee()->dbforge->create_table('friends_status_extra');

		unset($fields);

		$data = array(
			'class' => __CLASS__,
			'method' => 'update_cat_img_status',
			'hook' => 'friends_status_update_status',
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
		ee()->load->dbforge();
		ee()->dbforge->drop_table('friends_status_extra');

		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');
	}

	function update_cat_img_status($class, $data, $status_id) 
	{
		return var_dump($status_id);
	}

}
// END CLASS