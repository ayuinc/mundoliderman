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

	public function parameter() {
    $default = $this->EE->TMPL->fetch_param('default', '');
    $name = $this->EE->TMPL->fetch_param('name', $default);

    return $this->EE->config->item($name, $default);
  }

  public function is_user_lidermania() {
  	$user_lidermania = ee()->config->item("user_lidermania");
  	$username = $this->EE->session->userdata('username');

		return $username == $user_lidermania;
  }

  public function format_date() {
    $originalDate = $this->EE->TMPL->fetch_param('date', '');
    return date("d/m/Y", strtotime($originalDate));
  }

}