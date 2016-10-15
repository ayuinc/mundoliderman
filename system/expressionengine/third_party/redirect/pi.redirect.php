<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'Redirect Mundo Liderman',
	'pi_version' => '1.0',
	'pi_author' => 'Laboratoria',
	'pi_author_url' => 'http://laboratoria.la',
	'pi_description' => 'Plugin for Redirect',
	'pi_usage' => Redirect::usage()
);

class Redirect {

	public function __construct()
	{
		$this->EE =& get_instance();
		$this->CI =& get_instance();
	}

	public static function usage()
	{
		ob_start();
?>	

Documentation:

Plugin for Redirect

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function redirect_member(){
		$user_lidermania = ee()->config->item("user_lidermania");
		$member_id = $this->EE->session->userdata('member_id');

		// Redireccíonar a lidermania
		$username = $this->EE->session->userdata('username');

		if ($username == $user_lidermania) {
			$this->EE->functions->redirect("servicios/lidermania/$member_id");
			return;
		}

		if ($member_id > 0) {
			$group_id = $this->EE->session->userdata('group_id');
			if ($group_id == 6) {
				// Las chateadoras son redireccionadas al wall
				$this->EE->functions->redirect("wall");
			} else {
				// Los lidermans son redireccionados a su perfil
				$this->EE->functions->redirect("perfil/$member_id");
			}
		}
	}

	public function redirect_user_lidermania() {
		$user_lidermania = ee()->config->item("user_lidermania");
		$site_url = $this->EE->config->item("site_url");
		$member_id = $this->EE->session->userdata('member_id');
		$username = $this->EE->session->userdata('username');

		if ($username == $user_lidermania) {
			$this->EE->functions->redirect($site_url . "servicios/lidermania/$member_id");
			exit();
		}
	}

}