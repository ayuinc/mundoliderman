<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'Socket Mundo Liderman',
	'pi_version' => '1.0',
	'pi_author' => 'Laboratoria',
	'pi_author_url' => 'http://laboratoria.la',
	'pi_description' => 'Plugin for retreiving data from Mundo Liderman Web Service',
	'pi_usage' => Socket::usage()
);

class Socket {

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

}