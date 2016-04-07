<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'Util Mundo Liderman',
	'pi_version' => '1.0',
	'pi_author' => 'Laboratoria',
	'pi_author_url' => 'http://laboratoria.la',
	'pi_description' => 'Plugin for util',
	'pi_usage' => Util::usage()
);

class Util {

	public function __construct()
	{
		$this->EE =& get_instance();
		$this->CI =& get_instance();
	}

	public static function usage()
	{
		ob_start();
?>	

Documentation:

Plugin for utilities

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function can_delete_post_comments() {
		$group_id = $group_id = $this->EE->session->userdata('group_id');
		$groups = explode('|', $this->EE->config->item('post_delete_groups'));
		return in_array($group_id, $groups);
	}

}