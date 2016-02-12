<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class S3file_ft extends EE_Fieldtype {
	public $info = array(
		'name'		=> 'File S3',
		'version'	=> '1.0'
	);

	public $field_name		= 'file_s3_default';
	public $field_id		= 'file_s3_default';


	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}

	public function install()
	{
		return array(
				'folder' => ee()->config->item('bucket_folder')
			);
	}

	/**
	 * Display Field on Publish
	 *
	 * @access	public
	 * @param	existing data
	 * @return	field html
	 *
	 */
	public function display_field($data)
	{	
		$prep = "";

		if (isset($data)) {
			$path = $this->EE->config->item('s3_path');
			$folder = $this->settings['folder'];
			$link = $path . $folder . "/" . $data;
			$prep = "<a href='$link'>$data</a><br/>";
		}


		return $prep . form_input(array(
            'name'  => $this->field_name,
            'id'    => $this->field_id,
            'value' => $data,
            'type' => 'file'
        ));
	}

	public function display_settings() 
	{
		$folder = isset($data['folder']) ? $data['folder'] : $this->settings['folder'];

		ee()->table->add_row(
	        lang('folder', 'folder'),
	        form_input('folder', $folder)
	    );
	}

	public function save_settings($data)
	{
	    return array(
	        'folder'  => ee()->input->post('folder')
	    );
	}

	public function save($data)
	{	
		$folder = $this->settings['folder'];
		$filename = $this->_prefix_file() . "-" . (isset($_FILES[$this->field_name]) ? $_FILES[$this->field_name]['name'] : "");

		$upload_config = array();
		$upload_config['file_name'] = $filename;
		$upload_config['upload_path']          = "/" . $folder . "/";
        $upload_config['allowed_types']        = '*';
        $upload_config['ignore_path_exists'] = TRUE;
        $upload_config['overwrite']		= TRUE;

        $this->EE->load->library('upload', $upload_config);
        if ($this->EE->upload->do_upload_to_s3($this->field_name)){
        	return $this->EE->upload->data("file_name")["file_name"];
        }

        return $data;
	}

	public function pre_process($data)
	{	
		$path = $this->EE->config->item('s3_path');
		$folder = $this->settings['folder'];
		$link = $path . $folder . "/" . $data;
		return $link;
	}

	private function _prefix_file() {
		$now = new Datetime('now');
		return $now->getTimestamp();
	}
}