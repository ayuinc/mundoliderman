<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parameters_upd {

    public $version = '1.0';
    private $module_name = 'Parameters';

    /**
	* Install the module
	*
	* @return boolean TRUE
	*/
    function install() 
    {
    	ee()->load->dbforge();

    	$mod_data = array(
	        'module_name' => $this->module_name,
	        'module_version' => $this->version,
	        'has_cp_backend' => 'y',
	        'has_publish_fields' => 'n'
	    );

	    ee()->db->insert('modules', $mod_data);

	    if (!ee()->db->table_exists('parameter')) {
	    	$fields = array(
				'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'code' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE, 'unique' => TRUE),
				'description' => array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE),
				'value' => array('type' => 'varchar', 'constraint' => '250')
			);

			ee()->dbforge->add_field($fields);
			ee()->dbforge->add_key('id', TRUE);
			ee()->dbforge->create_table('parameter');

			$data = array(
				array(
					'code' => 'BOLETAS',
					'description' => 'Muestra Boletas de Pago',
					'value' => 'y'
				),
				array(
					'code' => 'CTS',
					'description' => 'Muestra CTS',
					'value' => 'y'
				),
				array(
					'code' => 'ACCESSSGI',
					'description' => 'Acceso a SGI',
					'value' => ''
				),
				array(
					'code' => 'ACCESSIND',
					'description' => 'Acceso a indicadores',
					'value' => ''
				)
			);

			ee()->db->insert_batch('parameter', $data);

			unset($fields);
			unset($data);
	    }

	    return TRUE;
    }

	/** 
	* Uninstall the module
	*
	* @return boolean TRUE
	*/
    function uninstall()
    {
    	ee()->load->dbforge();
    	ee()->db->select('module_id');
	    $query = ee()->db->get_where('modules', array('module_name' => $this->module_name));

	    ee()->db->where('module_id', $query->row('module_id'));
	    ee()->db->delete('module_member_groups');

	   	ee()->db->where("module_name", $this->module_name);
		ee()->db->delete("modules");

		ee()->dbforge->drop_table('parameter');

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

?>