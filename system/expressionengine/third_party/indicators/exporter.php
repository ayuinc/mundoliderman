<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exporter {

	public static function to_csv($headers, &$data, $filename) {
		header("Content-Disposition: attachment; filename=\"$filename.csv\"");
		header("Content-Type: text/csv; charset=utf-8");

		$file = fopen("php://output", 'w');

		fputcsv($file, $headers);

		foreach ($data as $row) {
			fputcsv($file, $row);
		}

		unset($data);
		fclose($file);
		die();
	}
}