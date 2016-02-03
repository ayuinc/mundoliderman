<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

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
	const RECLAMO = "Reclamo";
	const TOP_LIMIT = 5;

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
		$total_male = $this->total_male();
		$total_female = $this->total_users - $total_male;

		$data = array(
			'total_users' => $this->total_users,
			'total_male' => $total_male,
			'total_female' => $total_female,
			'p_male' => round(($total_male / $this->total_users)*100, 2),
			'p_female' => round(($total_female / $this->total_users)*100, 2)
		);

		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, array($data));
	}

	private function total_age($min, $max) {
		return 	$this->EE->db
					 ->from("members m")
					 ->join("member_data md", "m.member_id = md.member_id")
					 ->where(array(
					 	"md." . self::FIELD_AGE . " >=" => $min,
					 	"md." . self::FIELD_AGE . " <" => $max
					 ))
					 ->count_all_results();
	}

	public function age() {
		$total_age = $this->total_users;
		$data = array();
		$acum = 0;

		$ranges = array(
			array('min' => 18, 'max' => 25), 
			array('min' => 25, 'max' => 35),
			array('min' => 35, 'max' => 45),
			array('min' => 45, 'max' => 55),
			array('min' => 55, 'max' => 70));

		$total_range = count($ranges);
		for ($i=0; $i < ($total_range - 1); $i++) { 
			$range = $ranges[$i];
			$r_total = $this->total_age($range['min'], $range['max']);
			$acum += $r_total;
			$r_percentage = round(($r_total/$total_age) * 100, 2);
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
			'total' => $total_age - $acum,
			'percentage' => round((($total_age - $acum)/$total_age)*100, 2)
		);

		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	public function top_unidad() {
		$query = $this->EE->db
					 ->select(self::FIELD_UNIDAD . " as unidad, count(*) as total")
					 ->from("members m")
					 ->join("member_data md", "m.member_id = md.member_id")
					 ->group_by("unidad")
					 ->order_by('total', 'desc')
					 ->limit(self::TOP_LIMIT)
					 ->get();

		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$unidad_total = $result['total'];
			$result['percentage'] = round(($unidad_total/$this->total_users)*100, 2);
			$result['unidad'] = strtoupper($result['unidad']);
		}

		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	public function top_zona() {
		$query = $this->EE->db
					 ->select(self::FIELD_ZONA . " as zona, count(*) as total")
					 ->from("members m")
					 ->join("member_data md", "m.member_id = md.member_id")
					 ->group_by("zona")
					 ->order_by('total', 'desc')
					 ->limit(self::TOP_LIMIT)
					 ->get();

		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$zona_total = $result['total'];
			$result['percentage'] = round(($zona_total/$this->total_users)*100, 2);
			$result['zona'] = strtoupper($result['zona']);
		}

		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	public function top_ciudad() {
		$query = $this->EE->db
					 ->select(self::FIELD_CIUDAD . " as ciudad, count(*) as total")
					 ->from("members m")
					 ->join("member_data md", "m.member_id = md.member_id")
					 ->group_by("ciudad")
					 ->order_by('total', 'desc')
					 ->limit(self::TOP_LIMIT)
					 ->get();

		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$ciudad_total = $result['total'];
			$result['percentage'] = round(($ciudad_total/$this->total_users)*100, 2);
			$result['zona'] = strtoupper($result['zona']);
		}

		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	public function status() {
		$query = $this->EE->db
					->select("sc.category_name as categoria, count(*) as total")
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
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	public function premium() {
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
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	public function prominent() {
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
		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	public function indicadores_generales() {
		$data = array(
			'total_users' => $this->total_users,
			'total_posts' => $this->total_posts,
			'total_comments' => $this->total_comments,
			'post_por_liderman' => round($this->total_posts/$this->total_users, 2),
			'comments_por_liderman' => round($this->total_comments/$this->total_users, 2),
			'comments_por_post' => round($this->total_comments/$this->total_posts, 2)
		);

		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, array($data));
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

	public function reclamos_por_empresa() {
		$total_reclamos = $this->get_total_reclamos();
		$query = $this->EE->db
						->select("md." . self::FIELD_EMPRESA . " as empresa, count(*) as total")
						->from("exp_members m")
						->join("exp_member_data md", "md.member_id = m.member_id")
						->join("exp_wall_status ws", "ws.member_id = m.member_id")
						->join("exp_friends_status_category fsc", "fsc.category_id = ws.category_id")
						->where(array(
							'fsc.category_name' => self::RECLAMO
						))
						->group_by("empresa")
						->order_by("total", "desc")
						->limit(self::TOP_LIMIT)
						->get();
		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$empresa_total = $result['total'];
			$result['percentage'] = round(($empresa_total/$this->total_users)*100, 2);
		}

		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}

	public function reclamos_por_zona() {
		$total_reclamos = $this->get_total_reclamos();
		$query = $this->EE->db
						->select("md." . self::FIELD_ZONA . " as zona, count(*) as total")
						->from("exp_members m")
						->join("exp_member_data md", "md.member_id = m.member_id")
						->join("exp_wall_status ws", "ws.member_id = m.member_id")
						->join("exp_friends_status_category fsc", "fsc.category_id = ws.category_id")
						->where(array(
							'fsc.category_name' => self::RECLAMO
						))
						->group_by("zona")
						->order_by("total", "desc")
						->limit(self::TOP_LIMIT)
						->get();
		$data = $query->result_array();
		for ($i=0; $i < $query->num_rows(); $i++) { 
			$result =& $data[$i];
			$zona_total = $result['total'];
			$result['percentage'] = round(($zona_total/$this->total_users)*100, 2);
		}

		$tagdata = $this->EE->TMPL->tagdata;
		return $this->EE->TMPL->parse_variables($tagdata, $data);
	}
}