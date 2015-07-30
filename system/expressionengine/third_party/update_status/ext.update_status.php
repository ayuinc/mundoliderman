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
		ee()->load->dbforge();
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
		$fields = array(
			'status_id' => array('type' => 'int', 'unsigned' => TRUE),
			'category_id' => array('type' => 'int', 'unsigned' => TRUE),
			'image' => array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE)
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('status_id', TRUE);

		ee()->dbforge->create_table('friends_status_extra');

		unset($fields);

		$fields = array(
			'category_id' => array('type' => 'int', 'unsigned' => TRUE),
			'category_name' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE)
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('category_id', TRUE);

		ee()->dbforge->create_table('friends_status_category');

		unset($fields);

		$data = array(
			array(
				'category_id' => '0',
				'category_name' => 'Escoge una categoría'
			),
			array(
				'category_id' => '1',
				'category_name' => 'Quiero divertirme'
			),
			array(
				'category_id' => '2',
				'category_name' => 'Quiero dar mi testimonio'
			),
			array(
				'category_id' => '3',
				'category_name' => 'Tengo una solicitud'
			),
			array(
				'category_id' => '4',
				'category_name' => 'Tengo una consulta'
			),
			array(
				'category_id' => '5',
				'category_name' => 'Necesito ayuda'
			),
			array(
				'category_id' => '6',
				'category_name' => 'Otro'
			)
		);

		ee()->db->insert_batch('friends_status_category', $data);

		unset($data);

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
		ee()->dbforge->drop_table('friends_status_extra');
		ee()->dbforge->drop_table('friends_status_category');

		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');
	}

	function update_cat_img_status($class, $data, $status_id) 
	{
		if (ee()->db->table_exists('friends_status_extra')) 
		{
			$status_category = $data['status_category'];

			$extra_data = array();
			$extra_data['status_id'] = $status_id;
			$extra_data['category_id'] = $status_category;

			$msg = "";
			if (isset($data['status_image'])) {
				if ($data['status_image'] != null) {
					$image_data = $_FILES['status_image'];
					$server_path = ee()->config->item('server_path');
					$status_image_path = ee()->config->item('status_image_path');
					$target_dir = $server_path . $status_image_path;
					$target_file = $target_dir . basename($image_data["name"]);
					$uploadOk = 1;
					$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
					// Check if file already exists
					/*if (file_exists($target_file)) {
						$msg = "Sorry, file already exists!";
						$uploadOk = 0;
					}
					// Check file size
					if ($image_data["size"] > 500000) {
						$msg = "Sorry, your file is too long";
						$uploadOk = 0;
					}
					// Allow certain file formats
					if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
						$msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed";
						$uploadOk = 0;
					}*/
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						$msg = "Sorry, your file was not uploaded";
					}
					// if everything is ok, try to upload file
					else {
						if (move_uploaded_file($image_data["tmp_name"], $target_file)) {
							$msg = "The file " . basename($image_data["name"]) . " has been uploaded";
						} else {
							$msg = "Sorry, there was an error uploading your file";
						}
					}
					$extra_data['image'] = $status_image_path . basename($image_data["name"]);
				}
			} else {
				$extra_data['image'] = null;
			}

			ee()->db->query(ee()->db->insert_string('friends_status_extra', $extra_data));
		}
	}
}
// END CLASS