<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! class_exists('User'))
{
  require_once APPPATH . 'third_party/jobs/models/user.php';
}

$plugin_info = array(
  'pi_name' => 'Jobs Mundo Liderman',
  'pi_version' => '1.0',
  'pi_author' => 'Hugo Angeles',
  'pi_author_url' => 'https://github.com/hugoangeles0810',
  'pi_description' => 'Plugin for schedule jobs',
  'pi_usage' => Jobs::usage()
);

class Jobs {

  public function __construct()
  {
    $this->EE =& get_instance();
    $this->CI =& get_instance();
    $this->EE->load->library('curl');
    $this->EE->load->library('auth');
    $this->EE->load->library('logger');
    $this->EE->load->helper('url_helper');
    $this->EE->load->helper('security');
    $this->host = $this->EE->config->item('webservice_url');
    $this->secret = $this->EE->config->item('secret_jobs');
  }

  public static function usage()
  {
    ob_start();
?>  

Documentation:

Plugin para registrar ejecutar schedule jobs en Mundo Liderman

<?php
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
  }

  public function actualizar_lidermans() {
    // Ejecución sin limite de tiempo
    ini_set('max_execution_time', 0);
    $this->EE->logger->developer('Jobs: Inicio de proceso Actualizar Lidermans');
    if (!$this->check_access()) {
      $this->EE->logger->developer('Jobs: Intentado acceder via GET');
      exit('Error: Intentado acceder via GET');
    }

    $user = $this->EE->TMPL->fetch_param('user', $this->EE->config->item('user_jobs'));
    $pass = $this->EE->TMPL->fetch_param('pass', $this->EE->config->item('pass_jobs'));
    $secret = $this->EE->TMPL->fetch_param('secret', '');

    if ($secret != $this->secret ) {
      $this->EE->logger->developer('Jobs: Intentado acceder sin el secret key correcto');
      exit('Error: Intentado acceder sin el secret key correcto');
    }

    $token = $this->login($user, $pass);
    if (is_null($token) || $token == "") {
      $this->EE->logger->developer('Jobs: Error al obtener token de seguridad');
      exit("Error: No se logro obtener el token de seguridad");
    }

    $data = $this->datos_lidermans($token);
    
    if ($data == null) {
      $this->EE->logger->developer('Jobs: No se recibio data');
    }

    $totalRegistros = count($data);
    $totalInserciones = 0;
    $totalActualizaciones = 0;

    $this->EE->logger->developer('Jobs: Total de registros: ' . $totalRegistros);

    foreach ($data as $userJson) {
      $user = new User($userJson);

      if ($user->username != null) {
        $member_id = $user->get_member_id();
        if ($member_id == null) {
          ee()->db->query(ee()->db->insert_string('exp_members', $user->get_new_user_array()));
          $member_id = ee()->db->insert_id();
          $data = $user->get_member_data_array();
          $data["member_id"] = $member_id;
          ee()->db->query(ee()->db->insert_string('exp_member_data', $data));
          $totalInserciones += 1;
        } else {
          ee()->db->update(
              'member_data',
              $user->get_member_data_array(),
              array(
                  'member_id' => $member_id
              )
          );
          $totalActualizaciones += 1;
        }
      }
    }

    $this->EE->logger->developer('Jobs: Total de inserciones: ' . $totalInserciones);
    $this->EE->logger->developer('Jobs: Total de actualizaciones: ' . $totalActualizaciones);
    
    $this->EE->logger->developer('Jobs: Fin de proceso Actualizar Lidermans');
    exit("Jobs: Actualizar Lidermans Total: $totalRegistros Inserciones: $totalInserciones Actualizaciones: $totalActualizaciones\n");

  }


  public function inscribir_lidermans_a_capacitaciones() {
    // Ejecución sin limite de tiempo
    ini_set('max_execution_time', 0);
    $this->EE->logger->developer('Jobs: Inicio de proceso Inscripción Lidermans');
    if (!$this->check_access()) {
      $this->EE->logger->developer('Jobs: Intentado acceder via GET');
      exit('Error: Intentado acceder via GET');
    }

    $secret = $this->EE->TMPL->fetch_param('secret', '');

    if ($secret != $this->secret ) {
      $this->EE->logger->developer('Jobs: Intentado acceder sin el secret key correcto');
      exit('Error: Intentado acceder sin el secret key correcto');
    }

    $dateNow = date("Y-m-d", time());
    $capacitaciones = $this->get_capacitaciones_vigentes();
    $nuevasInscripciones = 0;

    foreach ($capacitaciones as $cap) {
        $liderman_ids = $this->get_lidermans_ids_por_tipo_unidad($cap->tipo_unidad);

        // Borrar inscripciones de lidermans que no pertenecen al tipo de unidad
        ee()->db->where('capacitacion_id', $cap->id)
            ->where_not_in('member_id', $liderman_ids)
            ->delete('inscripciones');

        foreach ($liderman_ids as $member_id) {
          if (!$this->esta_inscrito($member_id, $cap->id)) {
            $this->EE->db->insert('inscripciones', array(
                          'capacitacion_id' => $cap->id,
                          'member_id' => $member_id,
                          'fecha_inscripcion' => $dateNow
                        ));
            $nuevasInscripciones = $nuevasInscripciones + 1;
          }
        }

    }

    $this->EE->logger->developer('Jobs: Total nuevas Inscripciones:' . $nuevasInscripciones);
    $this->EE->logger->developer('Jobs: Fin de proceso Inscripción Lidermans');
  }

  private function datos_lidermans($token) {
    $url = $this->host . "/WSIntranet/LiderNet.svc/ListarDetallePorUnidad/-1/-1/$token";
    return $this->CI->curl->get($url);
  }

  private function check_access() {
    return $_SERVER['REQUEST_METHOD'] == "POST";
  }

  private function login($username, $password) {
    $url = $this->host . "/WSIntranet/Autenticacion.svc/AutenticacionUsuario/$username/$password";
    $data = $this->CI->curl->get($url);
    return $data["TokenSeguridad"];
  }

  private function get_capacitaciones_vigentes() {
    return $this->EE->db->select("cap.id as id,
                               cap.codigo as codigo,
                               cap.nombre as nombre,
                               cap.descripcion as descripcion,
                               cap.fecha_inicio as fecha_inicio,
                               cap.fecha_fin_vigencia as fecha_fin_vigencia,
                               cap.dias_plazo as dias_plazo,
                               cap.presencial as presencial,
                               cap.tipo_unidad as tipo_unidad")
                      ->from("capacitaciones cap")
                      ->where("cap.tipo_asignacion", "1")
                      ->where("CURDATE() <= cap.fecha_fin_vigencia")
                      ->get()
                      ->result();
  }

  private function get_lidermans_ids_por_tipo_unidad($tipo_unidad) {
    $rows = $this->EE->db->select("m.member_id as id")
                        ->from("members m")
                        ->join("member_data md", "md.member_id=m.member_id")
                        ->where("md.tipo_unidad_cod", $tipo_unidad)
                        ->get()->result_array();

    $ids = array();
    foreach ($rows as $row) {
      $ids[] = $row['id'];
    }

    return $ids;
  }

  private function esta_inscrito($member_id, $capacitacion_id) {
    return $this->EE->db->select("ins.member_id as member_id")
                        ->from("inscripciones ins")
                        ->where("ins.member_id", $member_id)
                        ->where("ins.capacitacion_id", $capacitacion_id)
                        ->get()->num_rows() > 0;
  }

}
