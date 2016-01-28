<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

use Aws\S3\S3Client;
use Aws\Common\Credentials\Credentials;

class S3 {

	private $bucket_name;
	private $client;

	public function __construct() {
		$this->EE =& get_instance();
		$this->bucket_name = $this->EE->config->item('s3_bucket');
		$access_key = $this->EE->config->item('access_key_id');
		$secret_key = $this->EE->config->item('secret_access_key');
		$credentials = new Credentials($access_key, $secret_key);
		$this->client = S3Client::factory(array(
								    'credentials' => $credentials
								));
	}
	
	public function uploadFile($pathToFile, $filename) {
		try {
			$result = $this->client->putObject(array(
				'Bucket' => $this->bucket_name,
				'Key' => $filename,
				'Body' => fopen($pathToFile, 'r+'),
				'ACL' => 'public-read'
			));

			return $result['ObjectURL'];
		} catch (Exception $e) {
			var_dump($e);exit;
			return null;
		}
	}

	private function isValidUpload($field) {
		if (!isset($_FILES[$field]))
		{
			return FALSE;
		}

		if (!is_uploaded_file($_FILES[$field]['tmp_name'])){
			return FALSE;
		}

		return TRUE;
	}

	private function isImage($field) {

		$png_mimes  = array('image/x-png');
		$jpeg_mimes = array('image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg');

		$file_type = preg_replace("/^(.+?);.*$/", "\\1", $_FILES[$field]['type']);
		$file_type = strtolower(trim(stripslashes($file_type), '"'));

		if (in_array($file_type, $png_mimes))
		{
			$file_type = 'image/png';
		}

		if (in_array($file_type, $jpeg_mimes))
		{
			$file_type = 'image/jpeg';
		}

		$img_mimes = array(
							'image/gif',
							'image/jpeg',
							'image/png',
						);

		return (in_array($file_type, $img_mimes, TRUE)) ? TRUE : FALSE;
	}

	public function uploadImage($field, $filename) {
		if (!$this->isValidUpload($field) || !$this->isImage($field)) {
			return null;
		}

		$pathToImg = $_FILES[$field]['tmp_name'];
		$filename .= "-" . $_FILES[$field]['name'];
		return $this->uploadFile($pathToImg, "/images/" . $filename);
	}

	public function uploadImageStatus($field, $filename) {
		return $this->uploadImage($field, "status/" . $filename);
	}

	public function uploadMemberPhoto($field, $filename) {
		return $this->uploadImage($field, "member_photos/" . $filename);
	}

}

/* End of file S3.php */