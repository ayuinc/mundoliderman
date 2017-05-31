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

  public function is_user_supervisor() {
    $group_id = $this->EE->session->userdata('group_id');
    return true;
  }

  public function format_date() {
    $originalDate = $this->EE->TMPL->fetch_param('date', '');
    return date("d/m/Y", strtotime($originalDate));
  }

  public function get_month_name() {
    $monthNumber = ee()->TMPL->fetch_param('month', '1');

    switch ($monthNumber) {
      case '1':
        return "Enero";
      case '2':
        return "Febrero";
      case '3':
        return "Marzo";
      case '4':
        return "Abril";
      case '5':
        return "Mayo";
      case '6':
        return "Junio";
      case '7':
        return "Julio";
      case '8':
        return "Agosto";
      case '9':
        return "Setiembre";
      case '10':
        return "Octubre";
      case '11':
        return "Noviembre";
      case '12':
        return "Diciembre";
      default:
        return "Enero";
    }
  }

  public function round() {
    $number = ee()->TMPL->fetch_param('number', '100');
    return round(doubleval($number));

  }

}