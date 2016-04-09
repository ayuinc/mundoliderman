<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'Web Service Mundo Liderman',
	'pi_version' => '1.0',
	'pi_author' => 'Laboratoria',
	'pi_author_url' => 'http://laboratoria.la',
	'pi_description' => 'Plugin for retreiving data from Mundo Liderman Web Service',
	'pi_usage' => Webservice::usage()
);

class Webservice {

	const MESES_ANT_TAREO = 3;
	const MESES_ANT_LIDERCARD = 24;
	const MESES_ANT_PRESTAMO = 24;

	public $return_data;
	private $custom_fields;
	private $salary_detail;

	private $host;

	public function __construct()
	{
		$this->EE =& get_instance();
		$this->CI =& get_instance();
		$this->EE->load->library('curl');
		$this->host = $this->EE->config->item('webservice_url');
	}

	public static function usage()
	{
		ob_start();
?>	

Documentation:

Plugin for retreiving data from Mundo Liderman's Web Service

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function boleta()
	{
		$member_id = trim($this->EE->TMPL->fetch_param('miembro', $this->current_member_id()));
		$codigoLiderman = $this->get_member_codigo($member_id);
		$token = $this->current_member_token();
		$periodo = trim($this->EE->TMPL->fetch_param('periodo'));
		//return "Código Liderman : ". $codigoLiderman . ", Token : " . $token . ", Periodo : " . $periodo;
		$url = $this->host . "/WSIntranet/BoletaPago.svc/TraerCabeceraBoletaPago/$codigoLiderman/$periodo/$token";
		$data = $this->EE->curl->get($url);
		if (count($data) > 0) {
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
		} else {
			return $this->EE->TMPL->no_results();
		}
	}

	public function detalle_boleta_haberes()
	{
		return $this->detalle_boleta();
	}

	public function detalle_boleta_descuentos()
	{
		return $this->detalle_boleta();
	}

	public function detalle_boleta_aportaciones()
	{
		return $this->detalle_boleta();
	}

	public function tareo()
	{
		$member_id = trim(ee()->TMPL->fetch_param('miembro', $this->current_member_id()));
		$codigoLiderman = $this->get_member_codigo($member_id);
		$token = $this->current_member_token();
		$mes = trim(ee()->TMPL->fetch_param('mes'));
		$year = intval(ee()->TMPL->fetch_param('year'));
		$currentYear = date('Y');
		$currentMonth = date('n');
		if (!isset($mes)) {
			$mes = $currentMonth;
		}
		$meses_anticipacion = ($currentYear - $year) * 12 + ($currentMonth - $mes) + 1;
		$url = $this->host . "/WSIntranet/Tareo.svc/ListarTareo/$codigoLiderman/$meses_anticipacion/$token";
		$data = $this->EE->curl->get($url);
		$tareo_description = $this->EE->db->select('code, description, bg_color')->get('tareo')->result_array();
		$new_data = array();
		$i = 0;
		foreach ($data as $key => $value) {
			if ($value["Mes"] == ($mes+1)) {
				$new_data[$i] = $value;
				$new_data[$i]["EventoDescripcion"] = $this->getEventDescription(trim($value["Evento"]), $tareo_description);
				$new_data[$i]["EventoBgColor"] = $this->getEventBgColor(trim($value["Evento"]), $tareo_description);
				$i++;
			}
		}
		if (count($new_data) > 0) {
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $new_data);
		} else {
			return $this->EE->TMPL->no_results();
		}
	}

	public function semaforotareo()
	{
		$member_id = $this->EE->TMPL->fetch_param('member', $this->current_member_id());
		$codigoLiderman = $this->get_member_codigo($member_id);
		$token = $this->current_member_token();
		$url = $this->host . "/WSIntranet/Tareo.svc/TraerSemaforoTareo/$codigoLiderman/" . self::MESES_ANT_TAREO . "/$token";
		$data = $this->EE->curl->get($url);
		$semaforoStatus = $data === 1; // 1 es verde
		$tagdata = array(['semaforo' => $semaforoStatus]);
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tagdata);
	}

	public function semaforolidernet()
	{
		$member_id = $this->EE->TMPL->fetch_param('member', $this->current_member_id());
		$dni = $this->get_member_dni($member_id);
		$token = $this->current_member_token();
		$url = $this->host . "/WSIntranet/LiderNet.svc/TraerSemaforoLiderNet/$dni/$token";
		$data = $this->EE->curl->get($url);
		$semaforoStatus = $data === 1; // 1 es verde
		$tagdata = array(['semaforo' => $semaforoStatus]);
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tagdata);
	}

	public function semaforolidercard()
	{
		$member_id = $this->EE->TMPL->fetch_param('member', $this->current_member_id());
		$codigoLiderman = $this->get_member_codigo($member_id);
		$token = $this->current_member_token();
		$url = $this->host . "/WSIntranet/LiderCard.svc/TraerSemaforoLiderCard/$codigoLiderman/" . self::MESES_ANT_LIDERCARD . "/$token";
		$data = $this->EE->curl->get($url);
		$semaforoStatus = $data === 1; // 1 es verde
		$tagdata = array(['semaforo' => $semaforoStatus]);
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tagdata);
	}

	public function semaforoprestamo()
	{
		$member_id = $this->EE->TMPL->fetch_param('member', $this->current_member_id());
		$codigoLiderman = $this->get_member_codigo($member_id);
		$token = $this->current_member_token();
		$url = $this->host . "/WSIntranet/Prestamo.svc/TraerSemaforoPrestamo/$codigoLiderman/" . self::MESES_ANT_PRESTAMO . "/$token";
		$data = $this->EE->curl->get($url);
		$semaforoStatus = $data === 1; // 1 es verde
		$tagdata = array(['semaforo' => $semaforoStatus]);
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tagdata);
	}

	public function semaforocapacitaciones()
	{
		$member_id = $this->EE->TMPL->fetch_param('member', $this->current_member_id());
		$dni = $this->get_member_dni($member_id);
		$token = $this->current_member_token();
		$month_end = strtotime('now', time());
		$month_start = strtotime('-3 month', time());
		$date_start = date('d-m-Y', $month_start);
		$date_end = date('d-m-Y', $month_end);
		$url = $this->host . "/WSIntranet/Capacitacion.svc/TraerSemaforoCapacitaciones/$dni/$date_start/$date_end/$token";
		$data = $this->EE->curl->get($url);
		$semaforoStatus = $data >= 3; // >= 3 verde
		$tagdata = array(['semaforo' => $semaforoStatus]);
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tagdata);
	}

	public function bonificaciones()
	{
		$member_id = $this->EE->session->userdata('member_id');
		$codigo_liderman_field_name = $this->getMemberFieldId("codigo-liderman");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$codigo_liderman_field_name, $token_field_name")
				         ->get('exp_member_data');
		$codigoLiderman = $query->row($codigo_liderman_field_name);
		$token = $query->row($token_field_name);
		$url = $this->host . "/WSIntranet/LiderCard.svc/ListarBonificaciones/$codigoLiderman/" . self::MESES_ANT_LIDERCARD . "/$token";
		$data = $this->EE->curl->get($url);
		if (count($data) > 0) {
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
		} else {
			return $this->EE->TMPL->no_results();
		}
	}

	public function meritosdemeritos()
	{
		$member_id = $this->EE->session->userdata('member_id');
		$codigo_liderman_field_name = $this->getMemberFieldId("codigo-liderman");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$codigo_liderman_field_name, $token_field_name")
				         ->get('exp_member_data');
		$codigoLiderman = $query->row($codigo_liderman_field_name);
		$token = $query->row($token_field_name);
		$url = $this->host . "/WSIntranet/LiderCard.svc/ListarMeritosDemerito/$codigoLiderman/" . self::MESES_ANT_LIDERCARD . "/$token";
		$data = $this->EE->curl->get($url);
		if (count($data) > 0) {
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
		} else {
			return $this->EE->TMPL->no_results();
		}
	}

	public function prestamos()
	{
		$member_id = $this->EE->session->userdata('member_id');
		$codigo_liderman_field_name = $this->getMemberFieldId("codigo-liderman");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$codigo_liderman_field_name, $token_field_name")
				         ->get('exp_member_data');
		$codigoLiderman = $query->row($codigo_liderman_field_name);
		$token = $query->row($token_field_name);
		$url = $this->host . "/WSIntranet/Prestamo.svc/ListarPrestamos/$codigoLiderman/" . self::MESES_ANT_PRESTAMO . "/$token";
		$data = $this->EE->curl->get($url);
		if (count($data) > 0) {
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
		} else {
			return $this->EE->TMPL->no_results();
		}
	}

	public function capacitaciones()
	{
		$member_id = $this->EE->session->userdata('member_id');
		$dni_field_name = $this->getMemberFieldId("dni");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$dni_field_name, $token_field_name")
				         ->get('exp_member_data');
		$dni = $query->row($dni_field_name);
		$token = $query->row($token_field_name);
		$month_end = strtotime('first day of this month', time());
		$month_start = strtotime('last day of -3 month', time());
		$date_start = date('d-m-Y', $month_start);
		$date_end = date('d-m-Y', $month_end);
		$url = $this->host . "/WSIntranet/Capacitacion.svc/ListarCapacitaciones/$dni/$date_start/$date_end/$token";
		$data = $this->EE->curl->get($url);
		if (count($data) > 0) {
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
		} else {
			return $this->EE->TMPL->no_results();
		}
	}

	private function getCustomMemberFields()
	{
		$member_fields = ee()->db->where('m_field_reg', 'y')
						 ->get("member_fields");
		$this->custom_fields = $member_fields->result_array();
	}

	private function getMemberFieldId($field_name)
	{
		$field_id = 0;
		if (!isset($this->custom_fields)) {
			$this->getCustomMemberFields();
		}
		foreach ($this->custom_fields as $key => $value) {
			if ($field_name == $value["m_field_name"]) {
				$field_id = $value["m_field_id"];
				break;
			}
		}
		return 'm_field_id_' . $field_id;
	}

	private function getEventDescription($code, $tareo = array()) 
	{
		$description = "";
	    foreach ($tareo as $key => $value) {
	        if ($value["code"] == $code) {
	            $description = $value["description"];
	            break;
	        }
	    }
	    return $description;
	}

	private function getEventBgColor($code, $tareo = array()) 
	{
		$bgColor = "";
	    foreach ($tareo as $key => $value) {
	        if ($value["code"] == $code) {
	            $bgColor = $value["bg_color"];
	            break;
	        }
	    }
	    return $bgColor;
	}

	private function detalle_boleta() 
	{
		$member_id = trim($this->EE->TMPL->fetch_param('miembro', $this->current_member_id()));
		$codigoLiderman = $this->get_member_codigo($member_id);
		$token = $this->current_member_token();
		$periodo = trim($this->EE->TMPL->fetch_param('periodo'));
		$url = $this->host . "/WSIntranet/BoletaPago.svc/TraerDetalleBoletaPago/$codigoLiderman/$periodo/$token";
		$data = $this->EE->curl->get($url);
		if (count($data) > 0) {
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
		} else {
			return $this->EE->TMPL->no_results();
		}
	}

	private function get_member_dni ($member_id) {
		$dni_field_name = $this->getMemberFieldId("dni");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$dni_field_name")
				         ->get('exp_member_data');
		$dni = $query->row($dni_field_name);
		return $dni;
	}

	private function get_member_codigo ($member_id) {
		$codigo_liderman_field_name = $this->getMemberFieldId("codigo-liderman");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$codigo_liderman_field_name, $token_field_name")
				         ->get('exp_member_data');
		$codigoLiderman = $query->row($codigo_liderman_field_name);
		return $codigoLiderman;
	}

	private function current_member_id () {
		return $this->EE->session->userdata('member_id');
	}

	private function current_member_token () {
		$token_field_name = $this->getMemberFieldId("token");
		$member_id = $this->current_member_id();
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$token_field_name")
				         ->get('exp_member_data');
		$token = $query->row($token_field_name);
		return $token;
	}

	public function cts() {
		$periodo = $this->EE->TMPL->fetch_param("periodo", "1");
		$member_id = $this->current_member_id();
		$codigoLiderman = $this->get_member_codigo($member_id);
		$token = $this->current_member_token();
		$url = $this->host . "/WSIntranet/RemuneracionesComputables.svc/RemuneracionesComputables/$codigoLiderman/$periodo/$token";
		$data = $this->EE->curl->get($url);
		if (count($data) > 0) {
			$data = $data[0];
			$this->format_cts_header($data);
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, array($data));
		}
		return $this->EE->TMPL->no_results();
	}

	public function cts_detalle() {
		$periodo = $this->EE->TMPL->fetch_param("periodo", "1");
		$member_id = $this->current_member_id();
		$codigoLiderman = $this->get_member_codigo($member_id);
		$token = $this->current_member_token();
		$url = $this->host . "/WSIntranet/RemuneracionesComputables.svc/RemuneracionesComputables/$codigoLiderman/$periodo/$token";
		$data = $this->EE->curl->get($url);
		$this->format_cts_detalle($data);
		if (count($data) > 0) {
			$data[0]["first_element"] = "1";
			$data[count($data)-1]["last_element"] = "1";
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
		}
		return $this->EE->TMPL->no_results();
	}

	public function cts_horas_extras() {
		$periodo = $this->EE->TMPL->fetch_param("periodo", "1");
		$member_id = $this->current_member_id();
		$codigoLiderman = $this->get_member_codigo($member_id);
		$token = $this->current_member_token();
		$urlHorasExtras = $this->host . "/WSIntranet/RemuneracionesComputables.svc/HorasExtras/$codigoLiderman/$periodo/$token";
		$urlCts = $this->host . "/WSIntranet/RemuneracionesComputables.svc/RemuneracionesComputables/$codigoLiderman/$periodo/$token";
		$data = $this->EE->curl->get($urlHorasExtras);
		$dataCts = $this->EE->curl->get($urlCts);
		if (count($data) > 0 && count($dataCts) > 0) {
			$data[0]["first_element"] = "1";
			$data[count($data)-1]["last_element"] = "1";
			$data = $this->format_cts_horas_extras($data);
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
		}
		return $this->EE->TMPL->no_results();
	}


	private function format_cts_header(&$data) {
		$data["Meses"] = substr($data["TiempoACancelar"], 2, 2);
		$data["Dias"] = substr($data["TiempoACancelar"], 4, 2);
		$data["FechaContrato"] = $data["FechaInicioContrato"];
		$data["TiempoACancelar"] = $this->format_tiempo($data["TiempoACancelar"]);
		$data["TiempoComputable"] = $this->format_tiempo($data["TiempoComputable"]);
		$data["TiempoFaltas"] = $this->format_tiempo($data["TiempoFaltas"]);
		$number = str_replace(",", ".", $data['IndemnizacionAnual']);
		$data["IndemnizacionAnual"] = number_format(floatval($number), 2);
		$number = str_replace(",", ".", $data['Tiempovalorizado']);
		$data["Tiempovalorizado"] = number_format(floatval($number), 2);
		$number = str_replace(",", ".", $data['TipoCambio']);
		$data["TipoCambio"] = number_format(floatval($number), 2);
	}

	private function format_cts_detalle(&$data) {
		for ($i=0; $i < count($data); $i++) { 
			$number = str_replace(",", ".", $data[$i]['DetalleMontoLocal']);
			$data[$i]['DetalleMontoLocal'] = number_format(floatval($number), 2);
		}
	}

	private function format_tiempo($tiempo) {
		if (strlen($tiempo) == 6) {
			return substr($tiempo, 0, 2) . " Años " . substr($tiempo, 2, 2) . " Meses " . substr($tiempo, 4, 2) . " Dias";
		}

		return $tiempo;
	}

	private function format_cts_horas_extras($data) {
		$months = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', 
						'04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
						'07' => 'Julio', '08' => 'Agosto', '09' => 'Setiembre',
						'10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

		$array = $data;
		$numElements = count($array);
		$dateBase = null;
		$suma  = 0;
		$numYear = "";
		$numMonth = "";
		if ($numElements > 0) {
			$periodo = $array[0]["Periodo"];
			$numYear = substr($periodo, 0, 4);
			$numMonth = substr($periodo, 4, 2);
		}

		for ($i=0; $i < $numElements; $i++) { 
			$dateBase = date_create("$numYear-$numMonth-01");
			$interval = date_interval_create_from_date_string(($numElements - $i - 1) . " months");
			$fecha = date_sub($dateBase, $interval);
			$array[$i]["Mes"] = $months[date_format($fecha, "m")] . " " . date_format($fecha, "Y");
			$array[$i]["count"] = $i + 1;
			$array[$i]["last_element"] = "0";
			$number = str_replace(",", ".", $array[$i]["Monto" . ($i + 1 )]);
			$monto = floatval($number);
			$array[$i]["Monto"] = number_format($monto, 2);
			$suma = $suma + $monto;
		}

		if ($numElements > 0) {
			$array[$numElements - 1]["last_element"] = "1";
			$array[$numElements - 1]["total"] = number_format($suma, 2);
			$array[$numElements - 1]["promedio"] = number_format($suma/$numElements, 2);
		}

		return $array;
	}

	public function utilidades() {
		$periodo = $this->EE->TMPL->fetch_param('periodo', date("Y"));
		$member_id = $this->current_member_id();
		$codigo = $this->get_member_codigo($member_id);
		$token = $this->current_member_token();
		$url = $this->host . "/WSIntranet/QuintaCategoria.svc/TraerQuintaCategoria/$codigo/$periodo/$token";
		$data = $this->EE->curl->get($url);
		if (count($data) > 0) {
			$this->format_utilidades($data);
			 return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
		}

		return $this->EE->TMPL->no_results();

	}

	private function format_utilidades(&$data) {
		$data[0]['MontoAntesImpuesto'] = $this->number_format($data[0]['MontoAntesImpuesto'], 2);
		$data[0]['PorcentajeRepartir'] = $this->number_format($data[0]['PorcentajeRepartir'], 2);
		$data[0]['MontoaRepartir'] = $this->number_format($data[0]['MontoaRepartir'], 2);
		$data[0]['DiasAnual'] = $this->number_format($data[0]['DiasAnual'], 0);
		$data[0]['DiasOrdinarios'] = $this->number_format($data[0]['DiasOrdinarios'], 0);
		$data[0]['UtilidadDias'] = $this->number_format($data[0]['UtilidadDias'], 2);
		$data[0]['SueldoAnual'] = $this->number_format($data[0]['SueldoAnual'], 2);
		$data[0]['Sueldo'] = $this->number_format($data[0]['Sueldo'], 2);
		$data[0]['UtilidadSueldo'] = $this->number_format($data[0]['UtilidadSueldo'], 2);
		$data[0]['TotalUtilidad'] = $this->number_format($data[0]['TotalUtilidad'], 2);
	}

	private function number_format($numberText, $precision = 2) {
		$number = str_replace(",", ".", $numberText);
		$number = floatval($number);
		return number_format($number, $precision);
	}
	
}