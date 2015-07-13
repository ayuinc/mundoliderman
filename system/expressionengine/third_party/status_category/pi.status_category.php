<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Status Category Class
 *
 * @package ExpressionEngine
 * @category Plugin
 * @author Laboratoria
 * @copyright Copyright (c) 2015, Laboratoria
 * @link http://laboratoria.la
 */

 $plugin_info = array(
 	'pi_name' => 'Status Category',
 	'pi_version' => '1.0',
 	'pi_author' => 'Laboratoria',
 	'pi_author_url' => 'http://laboratoria.la',
 	'pi_description' => 'Return a list of status categories',
 	'pi_usage' => Status_category::usage()
 );

class Status_category 
{
	
	public $return_data;

	public function __construct()
	{
		$this->EE =& get_instance();
	}

	public static function usage()
	{
		ob_start();
?>
Plugin that returns a list of status categories
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function all() 
	{
		$categories = $this->EE->db->get('friends_status_category');
		$data = json_decode(json_encode($categories->result()), true);
		return  $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
	}
}