<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'SGI Mundo Liderman',
	'pi_version' => '1.0',
	'pi_author' => 'Laboratoria',
	'pi_author_url' => 'http://laboratoria.la',
	'pi_description' => 'Plugin for view file SGI',
	'pi_usage' => SGI::usage()
);

class SGI {

	public static function usage()
	{
		ob_start();
?>	

Documentation:

Plugin for view file SGI

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function view() {
		$url = ee()->TMPL->fetch_param('url', '');
		$ext = $this->_get_file_extension($url);
		
		$encodedUrl = urlencode($url);
		$fixedEncodedUrl = str_replace(['%2F', '%3A'], ['/', ':'], $encodedUrl);
		if ($ext === 'pdf') {
			header("Content-type: application/pdf");
		} else {
			header("Content-type: application/octet-stream");
		}

		echo file_get_contents($fixedEncodedUrl);exit;
	}

	private function _get_file_extension($file) {
		$fileParts = explode(".", $file);
		return array_pop($fileParts);
	}


}