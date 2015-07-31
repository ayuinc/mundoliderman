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

	public $return_data;

	public function __construct()
	{
		$this->EE =& get_instance();
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

	public function authtest()
	{
		$dni = trim(ee()->TMPL->fetch_param('dni'));
		$url = "http://190.187.13.164/WSIntranet/Autenticacion.svc/AutenticacionUsuario/$dni/$dni";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		/*$auth = new AuthTest();
		$auth->setDNI($data["DNI"]);
		$auth->setCodigo($data["CodigoLiderman"]);
		$auth->setToken($data["TokenSeguridad"]);*/
		session_start();
		$_SESSION['auth'] = $data["TokenSeguridad"];
	}
	
	public function auth()
	{
		$dni = trim(ee()->TMPL->fetch_param('dni'));
		$url = "http://190.187.13.164/WSIntranet/Autenticacion.svc/AutenticacionUsuario/$dni/$dni";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		$tag_vars = array($data);
		return  $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tag_vars);
	}

	public function boleta()
	{
		$dni = trim(ee()->TMPL->fetch_param('dni'));
		$url = "http://190.187.13.164/WSIntranet/Autenticacion.svc/AutenticacionUsuario/$dni/$dni";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		$tag_vars = array($data);
		$codigoLiderman = $data["CodigoLiderman"];//trim($this->EE->TMPL->fetch_param('codigo'));
		$periodo = trim($this->EE->TMPL->fetch_param('periodo'));
		$token = $data["TokenSeguridad"];//trim($this->EE->TMPL->fetch_param('token'));
		$url = "http://190.187.13.164/WSIntranet/BoletaPago.svc/TraerCabeceraBoletaPago/$codigoLiderman/$periodo/$token";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
	}

	public function test()
	{
		return strval($_SESSION['auth']);
	}

	public function detalle_boleta()
	{
		$dni = trim(ee()->TMPL->fetch_param('dni'));
		$url = "http://190.187.13.164/WSIntranet/Autenticacion.svc/AutenticacionUsuario/$dni/$dni";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		$tag_vars = array($data);
		$codigoLiderman = $data["CodigoLiderman"];//trim($this->EE->TMPL->fetch_param('codigo'));
		$periodo = trim($this->EE->TMPL->fetch_param('periodo'));
		$token = $data["TokenSeguridad"];//trim($this->EE->TMPL->fetch_param('token'));
		$url = "http://190.187.13.164/WSIntranet/BoletaPago.svc/TraerDetalleBoletaPago/$codigoLiderman/$periodo/$token";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
	}

	public function tareo()
	{
		$dni = trim(ee()->TMPL->fetch_param('dni'));
		$url = "http://190.187.13.164/WSIntranet/Autenticacion.svc/AutenticacionUsuario/$dni/$dni";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		$tag_vars = array($data);
		$codigoLiderman = $data["CodigoLiderman"];//trim($this->EE->TMPL->fetch_param('codigo'));
		$mes = trim($this->EE->TMPL->fetch_param('mes'));
		$currentMonth = date('n');
		$mes = $currentMonth - $mes;
		$token = $data["TokenSeguridad"];//trim($this->EE->TMPL->fetch_param('token'));
		$url = "http://190.187.13.164/WSIntranet/Tareo.svc/ListarTareo/$codigoLiderman/$mes/$token";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		$new_data = array();
		$j = 0;
		for ($i=0; $i < count($data)-1; $i++) { 
			if ($data[$i]['Mes'] == '5') {
				$new_data[$j] = $data[$i];
				$j += 1;
			}
		}
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $new_data);
	}

	public function semaforotareo()
	{
		$dni = trim(ee()->TMPL->fetch_param('dni'));
		$url = "http://190.187.13.164/WSIntranet/Autenticacion.svc/AutenticacionUsuario/$dni/$dni";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		$tag_vars = array($data);
		$codigoLiderman = $data["CodigoLiderman"];//trim($this->EE->TMPL->fetch_param('codigo'));
		$mes = trim($this->EE->TMPL->fetch_param('mes'));
		$currentMonth = date('n');
		$mes = $currentMonth - $mes;
		$token = $data["TokenSeguridad"];//trim($this->EE->TMPL->fetch_param('token'));
		$url = "http://190.187.13.164/WSIntranet/Tareo.svc/TraerSemaforoTareo/$codigoLiderman/$mes/$token";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$content=curl_exec($ch);
		$json = json_decode($content, true);
		$data = $json["Resultado"];
		return $data;
	}

	public function getdatafromdb()
	{
		$channels = ee()->db->select('*')
		  ->from('members')
		  ->get();
		$data = '';
		if ($channels->num_rows() > 0)
		{
		    foreach($channels->result_array() as $row)
		    {
		        $data .= $row['username']."<br />\n";
		    }
		}
		return $data;
	}

}