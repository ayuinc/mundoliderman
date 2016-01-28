<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'Routes Mundo Liderman',
	'pi_version' => '1.0',
	'pi_author' => 'Laboratoria',
	'pi_author_url' => 'http://laboratoria.la',
	'pi_description' => 'Plugin for obtain routes',
	'pi_usage' => Routes::usage()
);

class Routes {

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

Plugin for retreiving data from Mundo Liderman's Socket

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function socket_url()
	{
		return $this->EE->config->item('socket_url');
	}

	public function S3_PATH(){
		return $this->EE->config->item('s3_path');
	}

}