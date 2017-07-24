<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Curl {
	
	public function get($url) 
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_TIMEOUT,5);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		return $data;
	}

}

/* End of file Curl.php */