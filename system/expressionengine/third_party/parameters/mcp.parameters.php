<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Parameters_mcp {
	
	public $return_data;

	function  __construct() 
	{ 
	    
	}

	function index()
	{
		ee()->load->library('javascript');
		ee()->load->library('table');
		ee()->load->helper('form');

		ee()->view->cp_page_title =  lang('parameters_module_name');

		$vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=parameters'.AMP.'method=update_parameter';
		$vars['form_hidden'] = NULL;
		$vars['files'] = array();

		$vars['options'] = array(
				'edit'  => lang('edit_selected')
				);

		$query = ee()->db->get('parameter');


		foreach($query->result_array() as $row) {
			$vars['parameters'][$row['code']]['description'] = $row['description'];
			$vars['parameters'][$row['code']]['id'] = $row['id'];
			$vars['parameters'][$row['code']]['value'] = $row['value'];
		}

		return ee()->load->view('index', $vars, TRUE);
	}

	function update_parameter() {
		$parameter_id = $_POST['id'];
		$parameter_val = $_POST['value'];

		ee()->db->where(array(
		    'id' => $parameter_id
		));

		ee()->db->update('parameter', array('value' => $parameter_val));

		ee()->session->set_flashdata('message_success', ee()->lang->line('updated'));
		ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=parameters'.AMP.'method=index');
	}


}