<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wall {
	
	public $return_data;

	// Constructor
	public function __construct()
	{
		$this->EE =& get_instance();
	}

	public function status_form()
	{
		$ret = $this->EE->TMPL->fetch_param("return");
		$ret = $this->EE->functions->fetch_site_index(TRUE) . $ret;
		// Build an array to hold the form's hidden fields
		$hidden_fields = array(
			"ACT" => $this->EE->functions->fetch_action_id("Wall", "wall_post"),
			"RET" => $ret
		);

		// Build an array with the form data
		$form_data = array(
			"id" => $this->EE->TMPL->form_id,
			"class" => $this->EE->TMPL->form_class,
			"hidden_fields" => $hidden_fields
		);

		// Fetch contents of the tag pair 
		$tagdata = $this->EE->TMPL->tagdata;

		$form = $this->EE->functions->form_declaration($form_data) . 
			$tagdata . "</form>";

		return $form;
	}

	public function delete_post_form()
	{
		// Build an array to hold the form's hidden fields
		$hidden_fields = array(
			"ACT" => $this->EE->functions->fetch_action_id("Wall", "delete_post")
		);

		// Build an array with the form data
		$form_data = array(
			"id" => $this->EE->TMPL->form_id,
			"class" => $this->EE->TMPL->form_class,
			"hidden_fields" => $hidden_fields
		);

		// Fetch contents of the tag pair
		$tagdata = $this->EE->TMPL->tagdata;

		$form = $this->EE->functions->form_declaration($form_data) .
			$tagdata . "</form>";

		return $form;
	}

	public function comment_post_form()
	{
		$post_id = $this->EE->TMPL->fetch_param("post_id", 0);
		// Build an array to hold the form's hidden fields
		$hidden_fields = array(
			"ACT" => $this->EE->functions->fetch_action_id("Wall", "comment_post"),
			"post_id" => $post_id
		);

		// Build an array with the form data
		$form_data = array(
			"id" => $this->EE->TMPL->form_id,
			"class" => $this->EE->TMPL->form_class,
			"hidden_fields" => $hidden_fields
		);

		// Fetch contents of the tag pair
		$tagdata = $this->EE->TMPL->tagdata;

		$form = $this->EE->functions->form_declaration($form_data) . 
			$tagdata . "</form>";

		return $form;
	}

	public function like_post_form()
	{
		$post_id = $this->EE->TMPL->fetch_param("post_id", 0);
		$member_id = $this->EE->session->userdata("member_id");

		$sql = "SELECT IFNULL(wl.like,'n') as post_like
				from exp_wall_like wl
				where wl.post_id = $post_id and wl.member_id = $member_id";

		$query = $this->EE->db->query($sql);
		$post_like = "n";
		if ($query->num_rows() > 0) {
			$post_like = $query->result_array()[0]["post_like"];
		}

		// Build an array to hold the form's hidden fields
		$hidden_fields = array(
			"ACT" => $this->EE->functions->fetch_action_id("Wall", "like_post"),
			"post_id" => $post_id,
			"post_like_status" => $post_like
		);

		// Build an array with the form data
		$form_data = array(
			"id" => $this->EE->TMPL->form_id,
			"class" => $this->EE->TMPL->form_class,
			"hidden_fields" => $hidden_fields
		);

		// Fetch contents of the tag pair
		$tagdata = $this->EE->TMPL->tagdata;

		$form = $this->EE->functions->form_declaration($form_data) .
			$tagdata . "</form>";

		return $form;
	}

	public function status()
	{
		$member_id = $this->EE->TMPL->fetch_param("member_id");
	
		$this->EE->db->select("ws.id as post_id, ws.member_id as post_user_id, m.screen_name as post_screen_name, m.username as post_username,
										ws.category_id as post_category_id, wsc.name as post_category_name, ws.content as post_content,
										ws.image_path as post_image_path, ws.status_date as post_status_date")
							  ->from("wall_status ws")
							  ->join("members m", "ws.member_id = m.member_id")
							  ->join("wall_status_category wsc", "ws.category_id = wsc.id")
							  ->where("ws.active", "y");
		
		if (!empty($member_id)) {
			$this->EE->db->where("ws.member_id", $member_id);
		} 

		$query = $this->EE->db->order_by("ws.status_date", "desc")->get();

		if ($query->num_rows() == 0) {
			return $this->EE->TMPL->no_results;
		} else {
			$data = $query->result_array();
			$tagdata = $this->EE->TMPL->tagdata;
			return $this->EE->TMPL->parse_variables($tagdata, $data);
		}
	}

	public function comment()
	{
		$post_id = $this->EE->TMPL->fetch_param('post_id', 0);
		$sql = "SELECT wc.post_id as comment_post_id, wc.comment_member_id, wc.comment, wc.comment_date,
				m.screen_name as comment_screen_name, m.username as comment_username
				from exp_wall_comment wc
				inner join exp_members m on wc.comment_member_id = m.member_id
				where post_id = $post_id
				order by wc.comment_date desc";
		
		$query = $this->EE->db->query($sql);

		if ($query->num_rows() == 0) {
			return $this->EE->TMPL->no_results;
		} else {
			$data = $query->result_array();
			$tagdata = $this->EE->TMPL->tagdata;
			return $this->EE->TMPL->parse_variables($tagdata, $data);
		}
	}

	public function wall_post()
	{
		$member_id = $this->EE->session->userdata("member_id");
		$category_id = $this->EE->input->post("status_category", TRUE);
		$post_content = $this->EE->input->post("wall_status", TRUE);
		$post_date = $this->EE->localize->now;

		$post_data = array(
			"member_id" => $member_id,
			"category_id" => $category_id,
			"content" => $post_content,
			"status_date" => $post_date,
			"active" => "y"
		);
		
		$this->EE->db->insert("wall_status", $post_data);
		$post_id = $this->EE->db->insert_id();

		$upload_config['upload_path']          = $this->EE->config->item("server_path") . $this->EE->config->item("status_image_path");
        $upload_config['allowed_types']        = 'gif|jpg|png';

        $this->EE->load->library('upload', $upload_config);
        if ( ! $this->EE->upload->do_upload("status_image"))
        {
                $error = array('error' => $this->EE->upload->display_errors());
        }
        else
        {
        		$image_path = $this->EE->config->item("status_image_path") . $this->EE->upload->data("file_name")["file_name"];
                $image_data = array(
                	"image_path" => $image_path
                );
                $this->EE->db->where("id", $post_id);
                $this->EE->db->update("wall_status", $image_data);
        }
        $return = $this->_prep_return();
        return $this->EE->functions->redirect($return);
	}

	public function delete_post() 
	{
		$post_id = $this->EE->input->post("post_id");
		$data = array(
			"active" => "n"
		);
		$this->EE->db->where("id", $post_id);
		$this->EE->db->update("wall_status", $data);
		return $this->EE->functions->redirect("wall");
	}

	public function comment_post()
	{
		$post_id = $this->EE->input->post("post_id");
		$comment_member_id = $this->EE->session->userdata("member_id");
		$comment = $this->EE->input->post("comment");
		$comment_date = $this->EE->localize->now;

		$data = array(
			"post_id" => $post_id,
			"comment_member_id" => $comment_member_id,
			"comment" => $comment,
			"comment_date" => $comment_date
		);

		$this->EE->db->insert("wall_comment", $data);
		$comment_post_id = $this->EE->db->insert_id();

		return $this->EE->functions->redirect("wall");
	}

	public function like_post()
	{
		$post_id = $this->EE->input->post("post_id");
		$like = $this->EE->input->post("post_like_status");
		$member_id = $this->EE->session->userdata("member_id");

		$data_where = array(
			'post_id' => $post_id,
			'member_id' => $member_id
		);

		$query = $this->EE->db->select('id')->from('wall_like')->where($data_where)->get();

		if ($like == "n") {
			$like = "y";
		} else {
			$like = "n";
		}

		if ($query->num_rows() > 0) {
			$data = array(
				'like' => $like
			);
			$this->EE->db->where($data_where);
			$this->EE->db->update('wall_like', $data);
		} else {
			$like_date = $this->EE->localize->now;
			$data = array(
				'post_id' => $post_id,
				'member_id' => $member_id,
				'like' => $like,
				'like_date' => $like_date
			);
			$this->EE->db->insert('wall_like', $data);
		}
		return $this->EE->functions->redirect("wall");
	}

	private function _prep_return( $return = '' )
	{
		if ( ee()->input->get_post('return') !== FALSE AND ee()->input->get_post('return') != '' )
		{
			$return	= ee()->input->get_post('return');
		}
		elseif ( ee()->input->get_post('RET') !== FALSE AND ee()->input->get_post('RET') != '' )
		{
			$return	= ee()->input->get_post('RET');
		}
		else
		{
			$return = ee()->functions->fetch_current_uri();
		}

		if ( preg_match( "/".LD."\s*path=(.*?)".RD."/", $return, $match ) )
		{
			$return	= ee()->functions->create_url( $match['1'] );
		}
		elseif ( stristr( $return, "http://" ) === FALSE && stristr( $return, "https://" ) === FALSE )
		{
			$return	= ee()->functions->create_url( $return );
		}

		return $return;
	}
}