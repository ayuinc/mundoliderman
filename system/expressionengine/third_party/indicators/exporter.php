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

	public static function to_csv_by_query($headers, $query, $filename) {
		header("Content-Disposition: attachment; filename=\"$filename.csv\"");
		header("Content-Type: text/csv; charset=utf-8");

		$file = fopen("php://output", 'w');

		fputcsv($file, $headers);

		$num_rows = $query->num_rows();
		if ($num_rows > 0) {
			$row = $query->first_row('array');
			for ($i=0; $i < $num_rows; $i++) { 
				fputcsv($file, $row);
				$row = $query->next_row('array');
			}
		}

		$query->free_result();
		fclose($file);
		die();
	}
}