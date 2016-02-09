<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! class_exists('Exporter'))
{
	require_once APPPATH . 'third_party/indicators/exporter.php';
}

$plugin_info = array(
	'pi_name' => 'Indicators Mundo Liderman',
	'pi_version' => '1.0',
	'pi_author' => 'Laboratoria',
	'pi_author_url' => 'http://laboratoria.la',
	'pi_description' => 'Plugin for obtain indicators',
	'pi_usage' => Indicators::usage()
);

class Indicators {

	const FIELD_SEX = "m_field_id_20";
	const FIELD_AGE = "m_field_id_17";
	const FIELD_UNIDAD = "m_field_id_6";
	const FIELD_ZONA = "m_field_id_7";
	const FIELD_CIUDAD = "m_field_id_25";
	const FIELD_EMPRESA = "m_field_id_4";
	const FIELD_PERIODO_PLANILLLA = "m_field_id_19";
	const RECLAMO = "Reclamo";
	const TOP_LIMIT = 5;
	const INTERVALO_PERIODO_PLANILLA = 60;

	private $total_users;
	private $total_posts;

	public function __construct()
	{
		$this->EE =& get_instance();
		$this->CI =& get_instance();
		$this->total_users = $this->get_total_users();
		$this->total_posts = $this->get_total_posts();
		$this->total_comments = $this->get_total_comments();
	}

	public static function usage()
	{
		ob_start();
?>	

Documentation:

Plugin for retreiving data from Mundo Liderman's Indicators

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function can_access() {
		$group_id = $group_id = $this->EE->session->userdata('group_id');
		$groups = explode('|', $this->EE->config->item('indicators_access'));
		return in_array($group_id, $groups);
	}

	public function get_total_users() {
		return $this->EE->db
					 ->from("members m")
					 ->join("member_data md", "m.member_id = md.member_id")
					 ->count_all_results();
	}

	public function get_total_posts() {
		return $this->EE->db
						->from("wall_status w")
						->join("friends_status_category sc", "sc.category_id = w.category_id")
						->where(array(
							'sc.category_id >' => 0
						))
						->count_all_results();
	}

	public function get_total_comments() {
		return $this->EE->db
						->from("wall_comment wc")
						->count_all_results();
	}

	public function total_male() {
		$data_where = array(
			self::FIELD_SEX => "M"
		);

		return $this->EE->db
					 ->from("members m")
					 ->join("member_data md", "m.member_id = md.member_id")
					 ->where($data_where)
					 ->count_all_results();
	}

	public function total_female() {
		$data_where = array(
			self::FIELD_SEX => "F"
		);

		return $this->EE->db
					 ->from("members m")
					 ->join("member_data md", "m.member_id = md.member_id")
					 ->where($data_where)
					 ->count_all_results();
	}

	public function sex() {
		$data = $this->_array_sex();
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, array($data));
	}

	private function _array_sex() {
		$total_male = $this->total_male();
		$total_female = $this->total_users - $total_male;

		$data = array(
			'total_users' => $this->total_users,
			'total_male' => $total_male,
			'total_female' => $total_female,
			'p_male' => round(($total_male / $this->total_users)*100, 2),
			'p_female' => round(($total_female / $this->total_users)*100, 2)
		);

		return $data;
	}

	public function age() {
		$data = $this->_array_age();
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	private function _total_age($min, $max) {
		return 	$this->EE->db
					 ->from("members m")
					 ->join("member_data md", "m.member_id = md.member_id")
					 ->where(array(
					 	"md." . self::FIELD_AGE . " >=" => $min,
					 	"md." . self::FIELD_AGE . " <" => $max
					 ))
					 ->count_all_results();
	}

	private function _age_ranges() {
		return array(
			array('min' => 18, 'max' => 25), 
			array('min' => 25, 'max' => 35),
			array('min' => 35, 'max' => 45),
			array('min' => 45, 'max' => 55),
			array('min' => 55, 'max' => 70));
	}

	private function _array_age() {
		$data = array();
		$acum = 0;

		$ranges = $this->_age_ranges();

		$total_range = count($ranges);
		for ($i=0; $i < ($total_range - 1); $i++) { 
			$range = $ranges[$i];
			$r_total = $this->_total_age($range['min'], $range['max']);
			$acum += $r_total;
			$r_percentage = round(($r_total/$this->total_users) * 100, 2);
			$data[] = array(
				'min' => $range['min'], 
				'max' => $range['max'], 
				'total' => $r_total,
				'percentage' => $r_percentage);
		}

		$range = $ranges[$total_range - 1];
		$data[] = array(
			'min' => $range['min'],
			'max' => $range['max'],
			'total' => $this->total_users - $acum,
			'percentage' => round((($this->total_users - $acum)/$this->total_users)*100, 2)
		);

		return $data;
	}

	public function tipo_usuario() {
		$data = $this->_array_tipo_usuario();
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	private function _array_tipo_usuario() {
		$query = $this->EE->db
					 ->select("mg.group_title AS grupo, count(*) AS total")
					 ->from("members m")
					 ->join("member_groups mg", "mg.group_id = m.group_id")
					 ->where(array(
					 	'mg.group_id >=' => 6
					 ))
					 ->group_by("mg.group_id")
					 ->order_by("total", "desc")
					 ->get();

		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$tipo_total = $result['total'];
			$result['percentage'] = round(($tipo_total/$this->total_users)*100, 2);
		}

		return $data;
	}

	public function periodo_planilla() {
		$data = $this->_array_periodo_planilla();

		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	private function _total_periodo_planilla($min, $max) {
		$periodo = self::FIELD_PERIODO_PLANILLLA;
		return $this->EE->db->query("SELECT COUNT(*) AS total
									FROM exp_members m
									INNER JOIN exp_member_data md ON md.member_id = m.member_id
									WHERE NOT md.$periodo IS NULL AND
										  md.$periodo >= $min AND
										  md.$periodo < $max
									")->row('total');
	}

	private function _max_periodo_planilla() {
		$periodo = self::FIELD_PERIODO_PLANILLLA;
		return $this->EE->db->query("SELECT MAX(CONVERT(md.$periodo, int)) AS total
									FROM exp_members m
									INNER JOIN exp_member_data md ON md.member_id = m.member_id
									WHERE NOT md.$periodo IS NULL")->row('total');
	}

	private function _rangos_perido_planilla($max_periodo, $interval) {
		$ranges = array();
		for ($i=0; $i < $max_periodo; $i = $i + $interval) { 
			$ranges[] = array('min' => $i, 'max' => $i + $interval);
		}

		return $ranges;
	}

	private function _array_periodo_planilla() {
		$data = array();

		$max_periodo = $this->_max_periodo_planilla();
		$total_periodo = $this->_total_periodo_planilla(0, $max_periodo);

		$ranges = $this->_rangos_perido_planilla($max_periodo, self::INTERVALO_PERIODO_PLANILLA);

		$total_range = count($ranges);
		for ($i=0; $i < ($total_range - 1); $i++) { 
			$range = $ranges[$i];
			$r_total = $this->_total_periodo_planilla($range['min'], $range['max']);
			$r_percentage = round(($r_total/$total_periodo) * 100, 2);
			$data[] = array(
				'min' => $range['min'], 
				'max' => $range['max'], 
				'total' => $r_total,
				'percentage' => $r_percentage);
		}

		$range = $ranges[$total_range - 1];
		$r_total = $this->_total_periodo_planilla($range['min'], $range['max'] + 1); // +1 para no sesgar el resultado en el ultimo rango
		$r_percentage = round(($r_total/$total_periodo) * 100, 2);
		$data[] = array(
			'min' => $range['min'], 
			'max' => $range['max'], 
			'total' => $r_total,
			'percentage' => $r_percentage);

		return $data;
	}

	public function top_unidad() {
		return $this->_top_generic('unidad', self::FIELD_UNIDAD);
	}

	public function top_zona() {
		return $this->_top_generic('zona', self::FIELD_ZONA);
	}

	public function top_ciudad() {
		return $this->_top_generic('ciudad', self::FIELD_CIUDAD);
	}

	private function _top_generic($name, $field) {
		$withLimit = $this->EE->TMPL->fetch_param('withLimit', TRUE);
		
		$data = $this->_array_top_generic($withLimit, $name, $field);

		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	private function _array_top_generic($with_limit, $name, $field) {
		$this->EE->db
					 ->select("$field as $name, count(*) as total")
					 ->from("members m")
					 ->join("member_data md", "m.member_id = md.member_id")
					 ->group_by("$name")
					 ->order_by('total', 'desc');

		if ($with_limit) {
			$this->EE->db->limit(self::TOP_LIMIT);
		}	

		$query = $this->EE->db->get();

		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$ciudad_total = $result['total'];
			$result['percentage'] = round(($ciudad_total/$this->total_users)*100, 2);
			$result[$name] = strtoupper($result[$name]);
		}
		return $data;
	}

	public function status() {
		$data = $this->_array_status();
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	private function _array_status() {
		$query = $this->EE->db
					->select("sc.category_id as categoria_id, sc.category_name as categoria, count(*) as total")
					->from("wall_status w")
					->join("friends_status_category sc", "sc.category_id = w.category_id")
					->where(array(
						'sc.category_id >' => 0
					))
					->group_by("sc.category_id")
					->order_by("sc.num_order")
					->get();

		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$categorie_total = $result['total'];
			$result['percentage'] = round(($categorie_total/$this->total_posts)*100, 2);
		}

		return $data;
	}

	public function premium() {
		$data = $this->_array_premium();
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	private function _array_premium() {
		$query = $this->EE->db
						->select("count(*) as total")
						->from("members m")
						->join("member_achievement ma", "m.member_id = ma.member_id")
						->where(array(
							'premium' => 'y'
						))
						->get();

		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$premium_total = $result['total'];
			$result['percentage'] = round(($premium_total/$this->total_posts)*100, 2);
		}

		return $data;
	}

	public function prominent() {
		$data = $this->_array_prominent();
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	private function _array_prominent() {
		$query = $this->EE->db
						->select("count(*) as total")
						->from("members m")
						->join("member_achievement ma", "m.member_id = ma.member_id")
						->where(array(
							'prominent' => 'y'
						))
						->get();

		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$premium_total = $result['total'];
			$result['percentage'] = round(($premium_total/$this->total_posts)*100, 2);
		}
		return $data;
	}

	public function indicadores_generales() {
		$data = $this->_array_indicadores_generales();
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, array($data));
	}

	private function _array_indicadores_generales() {
		return array(
			'total_users' => $this->total_users,
			'total_posts' => $this->total_posts,
			'total_comments' => $this->total_comments,
			'post_por_liderman' => round($this->total_posts/$this->total_users, 2),
			'comments_por_liderman' => round($this->total_comments/$this->total_users, 2),
			'comments_por_post' => round($this->total_comments/$this->total_posts, 2)
		);
	}

	public function reclamos_por_empresa() {
		return $this->_reclamos_generic('empresa', self::FIELD_EMPRESA);
	}

	public function reclamos_por_zona() {
		return $this->_reclamos_generic('zona', self::FIELD_ZONA);
	}

	private function _reclamos_generic($name, $field) {
		$withLimit = $this->EE->TMPL->fetch_param('withLimit', TRUE);
		$data = $this->_array_reclamos_generic($withLimit, $name, $field);
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	private function _array_reclamos_generic($with_limit, $name, $field) {
		$total_reclamos = $this->get_total_reclamos();
		$this->EE->db
					->select("md.$field as $name, count(*) as total")
					->from("members m")
					->join("member_data md", "md.member_id = m.member_id")
					->join("wall_status ws", "ws.member_id = m.member_id")
					->join("friends_status_category fsc", "fsc.category_id = ws.category_id")
					->where(array(
						'fsc.category_name' => self::RECLAMO
					))
					->group_by("$name")
					->order_by("total", "desc");		

		if ($with_limit) {
			$this->EE->db->limit(self::TOP_LIMIT);
		}

		$query = $this->EE->db->get();
		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$row_total = $result['total'];
			$result['percentage'] = round(($row_total/$total_reclamos)*100, 2);
		}

		return $data;
	}

	public function get_total_reclamos() {
		return $this->EE->db
						->from("exp_members m")
						->join("exp_member_data md", "md.member_id = m.member_id")
						->join("exp_wall_status ws", "ws.member_id = m.member_id")
						->join("exp_friends_status_category fsc", "fsc.category_id = ws.category_id")
						->where(array(
							'fsc.category_name' => self::RECLAMO
						))
						->count_all_results();
	}

	public function nombre_categoria() {
		$category_id = $this->EE->TMPL->fetch_param('categoryId', 0);
		return $this->EE->db
						->select("sc.category_name as category")
						->from("friends_status_category sc")
						->where(array(
							'sc.category_id' => $category_id
						))
						->get()->row('category');
	}

	private function total_posts_categoria($categoria_id) {
		return $this->EE->db
						->from("wall_status w")
						->join("friends_status_category sc", "sc.category_id = w.category_id")
						->where(array(
							'sc.category_id' => $categoria_id
						))
						->count_all_results();
	}

	public function post_por_categoria() {
		$category_id = $this->EE->TMPL->fetch_param('categoryId', 0);
		$filter_by = $this->EE->TMPL->fetch_param('filterBy', 'zona');
		$data = $this->_array_post_por_categoria($category_id, $filter_by);
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	private function _array_post_por_categoria($category_id, $filter_by) {
		$field = "";

		if ($filter_by === 'zona') {
			$field = self::FIELD_ZONA;
		} else if ($filter_by === 'unidad') {
			$field = self::FIELD_UNIDAD;
		}

		$total_categoria = $this->total_posts_categoria($category_id);

		$this->EE->db
					->select("md.$field as name, count(*) as total")
					->from("members m")
					->join("member_data md", "md.member_id = m.member_id")
					->join("wall_status ws", "ws.member_id = m.member_id")
					->join("friends_status_category fsc", "fsc.category_id = ws.category_id")
					->group_by("md.$field")
					->order_by("total", "desc");

		if ($category_id > 0) {
			$this->EE->db->where(array(
						'fsc.category_id' => $category_id
					));
		}

		$query = $this->EE->db->get();
		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$row_total = $result['total'];
			$result['percentage'] = round(($row_total/$total_categoria)*100, 2);
		}

		return $data;
	}

	public function export() {
		$code = $this->EE->TMPL->fetch_param('code', 1);
		$headers = array();
		$data = array();
		$filename = "";
		switch ($code) {
			case 1: // # Usuarios x Tipo de usuario
				$headers = array('tipo usuario', 'cantidad', 'porcentaje');
				$data = $this->_array_tipo_usuario();
				$filename = "usuarios_x_tipo_usuario";
				break;
			case 2: // # Usuarios x Edad
				$headers = array('desde', 'hasta', 'cantidad', 'porcentaje');
				$data = $this->_array_age();
				$filename = "usuarios_x_edad";
				break;
			case 3: // # Usuarios x Periodo planilla
				$headers = array('desde', 'hasta', 'cantidad', 'porcentaje');
				$data = $this->_array_periodo_planilla();
				$filename = "usuarios_x_periodo_planilla";
				break;
			case 4: // # Usuarios x Ciudad
				$headers = array('ciudad', 'cantidad', 'porcentaje');
				$data = $this->_array_top_generic(FALSE, 'ciudad', self::FIELD_CIUDAD);
				$filename = "usuarios_x_ciudad";
				break;
			case 5: // # Usuarios x Zona
				$headers = array('zona', 'cantidad', 'porcentaje');
				$data = $this->_array_top_generic(FALSE, 'zona', self::FIELD_ZONA);
				$filename = "usuarios_x_zona";
				break;
			case 6: // # Usuarios x Unidad
				$headers = array('unidad', 'cantidad', 'porcentaje');
				$data = $this->_array_top_generic(FALSE, 'unidad', self::FIELD_UNIDAD);
				$filename = "usuarios_x_unidad";
				break;
			case 7: // # Posts x Status
				$headers = array('id', 'categoria', 'cantidad', 'porcentaje');
				$data = $this->_array_status();
				$filename = "posts_x_categoria";
				break;
			case 8: // # Post de categoria x (zona|unidad)
				$category_id = $this->EE->TMPL->fetch_param('categoryId', 0);
				$filter_by = $this->EE->TMPL->fetch_param('filterBy', 'zona');
				$headers = array($filter_by, 'posts', 'porcentaje');
				$data = $this->_array_post_por_categoria($category_id, $filter_by);
				$filename = "posts_categoria_x_" . $filter_by;
				break;
		}
		$now = new Datetime('now');
		$filename .= "_" . $now->getTimestamp();
		Exporter::to_csv($headers, $data, $filename);
	}

}