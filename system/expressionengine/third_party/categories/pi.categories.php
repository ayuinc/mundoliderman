<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'N Level Categories',
	'pi_version' => '1.0',
	'pi_author' => 'Laboratoria',
	'pi_author_url' => 'http://laboratoria.la',
	'pi_description' => 'Categories Plugin to get subcategories based on parent id.',
	'pi_usage' => Categories::usage()
);

class Categories {

	public $return_data;
	private $custom_fields;
	private $salary_detail;

	public function __construct()
	{
		$this->EE =& get_instance();
	}

	public static function usage()
	{
		ob_start();
?>	

Documentation:

Categories Plugin to get subcategories based on parent id.

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function child_list() 
	{
		$parent_id = $this->EE->TMPL->fetch_param('parent_id', 0);
		$category_group = $this->EE->TMPL->fetch_param('category_group', 1);
		
		$data_where = array(
			'parent_id' => $parent_id,
			'group_id' => $category_group
		);

		$query = $this->EE->db->select("cat_id, group_id, parent_id, cat_name, cat_url_title")->where($data_where)->get("categories");
		$categories = $query->result_array();

		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $categories);
	}
}