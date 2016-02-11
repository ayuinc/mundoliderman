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
		$member_id = trim($this->EE->TMPL->fetch_param('miembro'));
		$codigo_liderman_field_name = $this->getMemberFieldId("codigo-liderman");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$codigo_liderman_field_name, $token_field_name")
				         ->get('exp_member_data');
		$codigoLiderman = $query->row($codigo_liderman_field_name);
		$token = $query->row($token_field_name);
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
		$member_id = trim(ee()->TMPL->fetch_param('miembro'));
		$codigo_liderman_field_name = $this->getMemberFieldId("codigo-liderman");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$codigo_liderman_field_name, $token_field_name")
				         ->get('exp_member_data');
		$codigoLiderman = $query->row($codigo_liderman_field_name);
		$token = $query->row($token_field_name);
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
		$member_id = $this->EE->session->userdata('member_id');
		$codigo_liderman_field_name = $this->getMemberFieldId("codigo-liderman");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$codigo_liderman_field_name, $token_field_name")
				         ->get('exp_member_data');
		$codigoLiderman = $query->row($codigo_liderman_field_name);
		$token = $query->row($token_field_name);
		$url = $this->host . "/WSIntranet/Tareo.svc/TraerSemaforoTareo/$codigoLiderman/" . self::MESES_ANT_TAREO . "/$token";
		$data = $this->EE->curl->get($url);
		$semaforoStatus = $data === 0; // 0 es verde
		$tagdata = array(['semaforo' => $semaforoStatus]);
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tagdata);
	}

	public function semaforolidernet()
	{
		$member_id = $this->EE->session->userdata('member_id');
		$dni_field_name = $this->getMemberFieldId("dni");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$dni_field_name, $token_field_name")
				         ->get('exp_member_data');
		$dni = $query->row($dni_field_name);
		$token = $query->row($token_field_name);
		$url = $this->host . "/WSIntranet/LiderNet.svc/TraerSemaforoLiderNet/$dni/$token";
		$data = $this->EE->curl->get($url);
		$semaforoStatus = $data === 1; // 1 es verde
		$tagdata = array(['semaforo' => $semaforoStatus]);
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tagdata);
	}

	public function semaforolidercard()
	{
		$member_id = $this->EE->session->userdata('member_id');
		$codigo_liderman_field_name = $this->getMemberFieldId("codigo-liderman");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$codigo_liderman_field_name, $token_field_name")
				         ->get('exp_member_data');
		$codigoLiderman = $query->row($codigo_liderman_field_name);
		$token = $query->row($token_field_name);
		$url = $this->host . "/WSIntranet/LiderCard.svc/TraerSemaforoLiderCard/$codigoLiderman/" . self::MESES_ANT_LIDERCARD . "/$token";
		$data = $this->EE->curl->get($url);
		$semaforoStatus = $data === 1; // 1 es verde
		$tagdata = array(['semaforo' => $semaforoStatus]);
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tagdata);
	}

	// http://190.187.13.164/WSIntranet/Prestamo.svc/TraerSemaforoPrestamo/CodigoLiderman/MesesAnticipacion/TokenSeguridad

	public function semaforoprestamo()
	{
		$member_id = $this->EE->session->userdata('member_id');
		$codigo_liderman_field_name = $this->getMemberFieldId("codigo-liderman");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$codigo_liderman_field_name, $token_field_name")
				         ->get('exp_member_data');
		$codigoLiderman = $query->row($codigo_liderman_field_name);
		$token = $query->row($token_field_name);
		$url = $this->host . "/WSIntranet/Prestamo.svc/TraerSemaforoPrestamo/$codigoLiderman/" . self::MESES_ANT_PRESTAMO . "/$token";
		$data = $this->EE->curl->get($url);
		$semaforoStatus = $data === 0; // 0 es verde
		$tagdata = array(['semaforo' => $semaforoStatus]);
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tagdata);
	}

	public function semaforocapacitaciones()
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
		$url = $this->host . "/WSIntranet/Capacitacion.svc/TraerSemaforoCapacitaciones/$dni/$date_start/$date_end/$token";
		$data = $this->EE->curl->get($url);
		$semaforoStatus = $data >= 3;
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
		$member_id = trim($this->EE->TMPL->fetch_param('miembro'));
		$codigo_liderman_field_name = $this->getMemberFieldId("codigo-liderman");
		$token_field_name = $this->getMemberFieldId("token");
		$query = $this->EE->db->where('member_id', $member_id)
						 ->select("$codigo_liderman_field_name, $token_field_name")
				         ->get('exp_member_data');
		$codigoLiderman = $query->row($codigo_liderman_field_name);
		$token = $query->row($token_field_name);
		$periodo = trim($this->EE->TMPL->fetch_param('periodo'));
		$url = $this->host . "/WSIntranet/BoletaPago.svc/TraerDetalleBoletaPago/$codigoLiderman/$periodo/$token";
		$data = $this->EE->curl->get($url);
		if (count($data) > 0) {
			return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
		} else {
			return $this->EE->TMPL->no_results();
		}
	}

}