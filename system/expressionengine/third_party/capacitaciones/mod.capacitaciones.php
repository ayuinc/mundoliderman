<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! class_exists('Exporter'))
{
  require_once APPPATH . 'third_party/indicators/exporter.php';
}

require_once FCPATH . 'mpdf/mpdf.php';

class Capacitaciones {

  var $field_unidad = "m_field_id_6";
  var $field_nombre = "m_field_id_12";
  var $field_apellidos = "m_field_id_13";
  var $field_dni = "m_field_id_16";

  const REPORT_PATH = APPPATH . 'third_party/capacitaciones/reports/';

  public function load_months() {
    $data = [];

    $member_id = $this->_get_member_id();

    if ($member_id == FALSE) {
      return ee()->TMPL->no_results;
    }

    $query = ee()->db->select(
                        "MONTH(cap.fecha_inicio) as date_month, YEAR(cap.fecha_inicio) as date_year")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->where("ins.member_id", $member_id)
                      ->or_where("asi.member_id", $member_id)
                      ->group_by(array("date_month", "date_year"))
                      ->order_by("date_year DESC, date_month DESC")
                      ->get();

    if ($query->num_rows() == 0) {
      return ee()->TMPL->no_results;
    } else {
      $data = $query->result_array();
      $tagdata = ee()->TMPL->tagdata;
      return ee()->TMPL->parse_variables($tagdata, $data);
    }
  }

  public function load_capacitaciones_by_month_and_year() {
    $month = ee()->TMPL->fetch_param('month', FALSE);
    $year = ee()->TMPL->fetch_param('year', FALSE);

    $data = [];

    $member_id = $this->_get_member_id();

    if ($member_id == FALSE || $month == FALSE || $year == FALSE) {
      return ee()->TMPL->no_results;
    }

    $query = ee()->db->select("cap.id as id,
                               cap.codigo as codigo,
                               cap.nombre as nombre,
                               cap.descripcion as descripcion,
                               cap.fecha_inicio as fecha_inicio,
                               cap.fecha_fin_vigencia as fecha_fin_vigencia,
                               cap.dias_plazo as dias_plazo,
                               cap.presencial as presencial")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->where("MONTH(cap.fecha_inicio) = $month", NULL, FALSE)
                      ->where("YEAR(cap.fecha_inicio) = $year", NULL, FALSE)
                      ->where("(ins.member_id = $member_id or asi.member_id=$member_id)", NULL, FALSE)
                      ->get();

    if ($query->num_rows() == 0) {
      return ee()->TMPL->no_results;
    } else {
      $data = $query->result_array();
      $tagdata = ee()->TMPL->tagdata;
      return ee()->TMPL->parse_variables($tagdata, $data);
    }
  }

  public function load_capacitacion_by_id() {
    $id = ee()->TMPL->fetch_param('id', FALSE);
    $member_id = $this->_get_member_id();

    if ($id === FALSE || $member_id == FALSE) {
      return ee()->TMPL->no_results;
    }

     $query = ee()->db->select("cap.id as id,
                               cap.codigo as codigo,
                               cap.nombre as nombre,
                               cap.descripcion as descripcion,
                               cap.fecha_inicio as fecha_inicio,
                               cap.fecha_fin_vigencia as fecha_fin_vigencia,
                               cap.dias_plazo as dias_plazo,
                               cap.presencial as presencial")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->where("cap.id", $id)
                      ->where("(ins.member_id = $member_id or asi.member_id=$member_id)", NULL, FALSE)
                      ->get();

    if ($query->num_rows() == 0) {
      return ee()->TMPL->no_results;
    } else {
      $data = $query->result_array();
      $tagdata = ee()->TMPL->tagdata;
      return ee()->TMPL->parse_variables($tagdata, $data);
    }

  }

  private function _contenidos_capacitacion($capacitacion_id) {
    $query = ee()->db->select("c.id as id,
                              c.nombre as nombre,
                              c.descripcion as descripcion,
                              c.file_path as file_path,
                              c.video_id as video_id,
                              c.orden as orden")
                    ->from("contenidos c")
                    ->where("capacitacion_id", $capacitacion_id)
                    ->order_by("c.orden", "ASC")
                    ->get();

    return $query;
  }


  public function load_contenidos() {
    $capacitacion_id = ee()->TMPL->fetch_param('capacitacion', FALSE);
    $page = ee()->TMPL->fetch_param('page', "0");

    $member_id = $this->_get_member_id();

    if ($capacitacion_id === FALSE || $member_id == FALSE) {
      return ee()->TMPL->no_results;
    }

    $query = $this->_contenidos_capacitacion($capacitacion_id);

    if ($query->num_rows() == 0) {
      return ee()->TMPL->no_results;
    }

    $data = $query->result_array();
    $count = count($data);

    if ($page >= $count - 1) {
      $page = $count - 1;
    }

    if ($page <= 0) {
      $page = 0;
    }

    $pageArray = array_slice($data, $page, 1);
    $nextPage = $page >= $count - 1 ? "" : $page + 1;
    $prevPage = $page <= 0 ? "" : $page - 1;

    $pageArray[0]['next_page'] = $nextPage;
    $pageArray[0]['prev_page'] = $prevPage;

    $tagdata = ee()->TMPL->tagdata;
    return ee()->TMPL->parse_variables($tagdata, $pageArray);

  }


  public function test_form() {
    $ret = ee()->TMPL->fetch_param("return");
    $ret = ee()->functions->fetch_site_index(TRUE) . $ret;

    $hidden_fields = array(
      "ACT" => ee()->functions->fetch_action_id("Capacitaciones", "test_post"),
      "RET" => $ret
    );

    $form_data = array(
      "id" => ee()->TMPL->form_id,
      "class" => ee()->TMPL->form_class,
      "hidden_fields" => $hidden_fields
    );

    // Fetch contents of the tag pair 
    $tagdata = ee()->TMPL->tagdata;

    $form = ee()->functions->form_declaration($form_data) . 
      $tagdata . "</form>";

    return $form;
  }

  public function load_test_questions() {
    ee()->load->model('capacitacion_model');
    $capacitacion_id = ee()->TMPL->fetch_param('capacitacion', FALSE);
    ee()->capacitacion_model->load($capacitacion_id);
    $member_id = $this->_get_member_id();

    if ($capacitacion_id === FALSE || $member_id == FALSE) {
      return ee()->TMPL->no_results;
    }

    $query = ee()->db->select("p.id as pregunta_id,
                               p.nombre as pregunta_nombre")
                      ->from("preguntas p")
                      ->where("p.capacitacion_id", $capacitacion_id)
                      ->get();


    if ($query->num_rows() == 0) {
      return ee()->TMPL->no_results;
    } else {
      $data = $query->result_array();
      $questions = $this->_get_random_questions($data, ee()->capacitacion_model->cant_preguntas);
      $tagdata = ee()->TMPL->tagdata;
      return ee()->TMPL->parse_variables($tagdata, $questions);
    }
  }

  public function load_answers_items() {
    $pregunta_id = ee()->TMPL->fetch_param('pregunta', FALSE);
    $member_id = $this->_get_member_id();

    if ($pregunta_id === FALSE || $member_id == FALSE) {
      return ee()->TMPL->no_results;
    }

    $query = ee()->db->select("o.id as opcion_id,
                               o.nombre as opcion_nombre,
                               o.es_respuesta as opcion_respuesta")
                      ->from("pregunta_opciones o")
                      ->where("o.pregunta_id", $pregunta_id)
                      ->get();

    if ($query->num_rows() == 0) {
      return ee()->TMPL->no_results;
    } else {
      $data = $query->result_array();
      $tagdata = ee()->TMPL->tagdata;
      return ee()->TMPL->parse_variables($tagdata, $data);
    }
  }

  public function test_post() {
    $capacitacion_id = ee()->input->post("capacitacion", TRUE);
    $member_id = $this->_get_member_id();
    $answers = ee()->input->post("answers", TRUE);

    if ($answers == FALSE) {
      $answers = array();
    }

    $porcentajeAprobacion = floatval(ee()->db->select("porcentaje_aprobacion")
                                    ->from("capacitaciones")
                                    ->where("id", $capacitacion_id)
                                    ->get()->row()->porcentaje_aprobacion);

    $preguntas = ee()->db->select("p.id as pregunta_id,
                               o.id as opcion_correcta")
                     ->from("preguntas p")
                     ->join("pregunta_opciones o", "p.id = o.pregunta_id")
                     ->where("p.capacitacion_id", $capacitacion_id)
                     ->where("o.es_respuesta", "1") // Es respuesta correcta
                     ->get()->result_array();

    $puntaje = 0;
    $countPreguntas = count($answers);
    $puntajePregunta = 100.0/$countPreguntas;

    $solucionario = array();

    foreach ($preguntas as $pregunta) {
      if (isset($answers[$pregunta["pregunta_id"]])) {
        $solucionario[] = array(
          'id' => $pregunta["pregunta_id"],
          'respuesta' => $pregunta["opcion_correcta"]
        );

        if ($answers[$pregunta["pregunta_id"]][0] == $pregunta["opcion_correcta"]) {
          $puntaje += $puntajePregunta;
        }
      }
    }

    $query = ee()->db->select('id')
                  ->from('test_resultados')
                  ->where('capacitacion_id', $capacitacion_id)
                  ->where('member_id', $member_id)
                  ->get();

    $id = NULL;

    if ($query->num_rows() > 0) {
      $id = $query->result()[0]->id;
    }

    $testResultado =  array(
      'capacitacion_id' => $capacitacion_id,
      'member_id' => $member_id,
      'puntaje' => $puntaje,
      'porcentaje_aprobacion' => $porcentajeAprobacion,
      'fecha' => date('Y-m-d H:i:s'),
      'estado' => $puntaje >= $porcentajeAprobacion ? "a" : "d"
    );

    if ($id == NULL) { // Insert
      ee()->db->insert('test_resultados', $testResultado); 
    } else { // Update
      ee()->db->where('id', $id);
      ee()->db->update('test_resultados', $testResultado);
    }

    $resultadoTest = $puntaje >= $porcentajeAprobacion ? "aprobado" : "desaprobado";

    $response = array(
      "puntaje" => $puntaje,
      "estado" => $resultadoTest
    );

    if ($resultadoTest == "aprobado") {
      $response['solucionario'] = $solucionario;
    }
    
    echo json_encode($response);
  }

  public function test_result() {
    $capacitacion_id = ee()->TMPL->fetch_param('capacitacion', FALSE);
    $member_id = $this->_get_member_id();

    if ($capacitacion_id === FALSE || $member_id == FALSE) {
      return ee()->TMPL->no_results;
    }

    $query = ee()->db->select("r.id as resultado_id,
                               r.puntaje as resultado_puntaje,
                               r.porcentaje_aprobacion as resultado_porcentaje_aprobacion,
                               r.fecha as resultado_fecha,
                               r.estado as resultado_estado")
                      ->from('test_resultados r')
                      ->where('capacitacion_id', $capacitacion_id)
                      ->where('member_id', $member_id)
                      ->get();

    if ($query->num_rows() == 0) {
      return ee()->TMPL->no_results;
    } else {
      $data = $query->result_array();
      $tagdata = ee()->TMPL->tagdata;
      return ee()->TMPL->parse_variables($tagdata, $data);
    }

  }

  public function estado_capacitacion() {
    $capacitacion_id = ee()->TMPL->fetch_param('capacitacion', FALSE);
    $member_id = $this->_get_member_id();
    $capacitacion = ee()->db->select("cap.id as id,
                               cap.codigo as codigo,
                               cap.nombre as nombre,
                               cap.descripcion as descripcion,
                               cap.fecha_inicio as fecha_inicio,
                               cap.fecha_fin_vigencia as fecha_fin_vigencia,
                               cap.dias_plazo as dias_plazo,
                               cap.presencial as presencial,
                               ins.fecha_inscripcion as fecha_inscripcion")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->where("cap.id", $capacitacion_id)
                      ->where("(ins.member_id = $member_id or asi.member_id=$member_id)", NULL, FALSE)
                      ->get()->result()[0];

    $query = ee()->db->select("r.id as resultado_id,
                               r.puntaje as resultado_puntaje,
                               r.porcentaje_aprobacion as resultado_porcentaje_aprobacion,
                               r.fecha as resultado_fecha,
                               r.estado as resultado_estado")
                      ->from('test_resultados r')
                      ->where('capacitacion_id', $capacitacion_id)
                      ->where('member_id', $member_id)
                      ->get();

    $capacitacion_resultado = NULL;
    if ($query->num_rows() > 0) {
      $capacitacion_resultado = $query->result()[0];
    }

    if ($capacitacion->presencial == 1) {
      return "finalizada aprobado";
    }

    return $this->_get_estado_capacitacion($capacitacion);
  }

  public function resultado_capacitacion() {
    $capacitacion_id = ee()->TMPL->fetch_param('capacitacion', FALSE);
    $member_id = $this->_get_member_id();

    return $this->_resultado_capacitacion($member_id, $capacitacion_id);
  }

  private function _resultado_capacitacion($member_id, $capacitacion_id) {
    $query = ee()->db->select("r.id as resultado_id,
                               r.puntaje as resultado_puntaje,
                               r.porcentaje_aprobacion as resultado_porcentaje_aprobacion,
                               r.fecha as resultado_fecha,
                               r.estado as resultado_estado")
                      ->from('test_resultados r')
                      ->where('capacitacion_id', $capacitacion_id)
                      ->where('member_id', $member_id)
                      ->get();


    if ($query->num_rows() == 0) {
      return "";
    } else {
      $resultado = $query->result_array()[0];
      return $resultado['resultado_estado'] == 'a' ? "aprobado" : "desaprobado";
    }
  }

  public function tiene_capacitaciones_pendientes() {
    $month = ee()->TMPL->fetch_param('month', FALSE);
    $year = ee()->TMPL->fetch_param('year', FALSE);

    $member_id = $this->_get_member_id();

    if ($member_id == FALSE || $month == FALSE || $year == FALSE) {
      return "0";
    }

    $capacitaciones = ee()->db->select("cap.id as id,
                               cap.codigo as codigo,
                               cap.nombre as nombre,
                               cap.descripcion as descripcion,
                               cap.fecha_inicio as fecha_inicio,
                               cap.fecha_fin_vigencia as fecha_fin_vigencia,
                               cap.dias_plazo as dias_plazo,
                               cap.presencial as presencial,
                               ins.fecha_inscripcion as fecha_inscripcion")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->where("MONTH(cap.fecha_inicio) = $month", NULL, FALSE)
                      ->where("YEAR(cap.fecha_inicio) = $year", NULL, FALSE)
                      ->where("ins.member_id", $member_id)
                      ->or_where("asi.member_id", $member_id)
                      ->get()->result();

    $tienePendientes = "0";
    $dateNow = new DateTime();
    foreach ($capacitaciones as $cap) {

      if ($cap->presencial == 1) {
        continue;
      }

      $resultadoCapacitacion = $this->_resultado_capacitacion($member_id, $cap->id);
      if ($resultadoCapacitacion != "aprobado") {
        $tienePendientes = "1";
        break;
      }
    }

    return $tienePendientes;
  }

  public function tiene_capacitaciones() {
    $member_id = $this->_get_member_id();

    $query = ee()->db->select("cap.id as id,
                               cap.codigo as codigo,
                               cap.nombre as nombre,
                               cap.descripcion as descripcion,
                               cap.fecha_inicio as fecha_inicio,
                               cap.fecha_fin_vigencia as fecha_fin_vigencia,
                               cap.dias_plazo as dias_plazo,
                               cap.presencial as presencial,
                               ins.fecha_inscripcion as fecha_inscripcion")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->where("(ins.member_id = $member_id or asi.member_id=$member_id)", NULL, FALSE)
                      ->get();

    if ($query->num_rows() == 0) {
      return "0";
    } else {
      return "1";
    }
  }

  public function semaforo_capacitaciones() {
    $member_id = ee()->TMPL->fetch_param('member', $this->_get_member_id());

    $query = ee()->db->select("cap.id as id,
                               cap.codigo as codigo,
                               cap.nombre as nombre,
                               cap.descripcion as descripcion,
                               cap.fecha_inicio as fecha_inicio,
                               cap.fecha_fin_vigencia as fecha_fin_vigencia,
                               cap.dias_plazo as dias_plazo,
                               cap.presencial as presencial,
                               ins.fecha_inscripcion as fecha_inscripcion")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->where("(ins.member_id = $member_id or asi.member_id=$member_id)", NULL, FALSE)
                      ->get();

    $semaforo = true;                  
    if ($query->num_rows() != 0) {
      $capacitaciones = $query->result();

      foreach ($capacitaciones as $cap) {
        if ($cap->presencial == 1) {
          continue;
        }

        $estado = $this->_get_estado_capacitacion($cap);
        $resultado = $this->_resultado_capacitacion($member_id, $cap->id);

        // Si la capacitación finalizo y desaprobo el test o no lo rindio
        if ($estado == "finalizada" && ($resultado == "" || $resultado == "desaprobado")) {
          $semaforo = false;
          break;
        }

      }
    }

    $tagdata = array(['semaforo' => $semaforo]);
    return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $tagdata);
  }

  public function capacitaciones_supervisor() {
    $unidad = $this->_get_unidad_of_current_member();
    ee()->db->distinct();
    $query = ee()->db->select("cap.id as capacitacion_id,
                               cap.nombre as capacitacion_nombre")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->join("member_data md", "md.member_id = ins.member_id or md.member_id = asi.member_id")
                      ->where("md.$this->field_unidad", $unidad)
                      ->get();

    if ($query->num_rows() == 0) {
      return ee()->TMPL->no_results;
    } else {
      $data = $query->result_array();
      $tagdata = ee()->TMPL->tagdata;
      return ee()->TMPL->parse_variables($tagdata, $data);
    }
  }

  public function lidermans_capacitaciones_supervisor() {

    $capacitacion = ee()->TMPL->fetch_param('capacitacion', "0");
    $unidad = $this->_get_unidad_of_current_member();

    $query = ee()->db->select("cap.nombre as capacitacion_nombre,
                              md.$this->field_apellidos as member_apellidos,
                              md.$this->field_nombre as member_nombre,
                              md.$this->field_dni as member_dni,
                              md.member_id as member_id,
                              cap.id as capacitacion_id,
                              ins.fecha_inscripcion as fecha_inscripcion,
                              cap.dias_plazo as dias_plazo,
                              cap.presencial as presencial")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->join("member_data md", "md.member_id = ins.member_id or md.member_id = asi.member_id")
                      ->where("md.$this->field_unidad", $unidad);

    if ($capacitacion != "0") {
      $query->where("cap.id", $capacitacion);
    }

    $query->group_by(array("md.member_id", "cap.id"));
    $query = $query->get();

    if ($query->num_rows() == 0) {
      return ee()->TMPL->no_results;
    } else {
      $data = $query->result_array();

      foreach ($data as &$row) {
        $resumen = "verde";

        if ($row['presencial'] != "1") {
          $resultado =  $this->_resultado_capacitacion($row['member_id'], $row['capacitacion_id']);
          $estado = $this->get_estado_capacitacion($row['fecha_inscripcion'], $row['dias_plazo']);

          if ($estado == "finalizada" && ($resultado == "" || $resultado == "desaprobado")) {
            $resumen = "rojo";
          } elseif ($estado == "encurso" && $resultado == "") {
            $resumen = "amarillo";
          }
        }

        $row['resumen'] = $resumen;
      }

      $tagdata = ee()->TMPL->tagdata;
      return ee()->TMPL->parse_variables($tagdata, $data);
    }
  }

  public function exportar_lidermans_supervisor() {
    $capacitacion = ee()->TMPL->fetch_param('capacitacion', "0");
    
    $unidad = $this->_get_unidad_of_current_member();

    $query = ee()->db->select("md.$this->field_dni as member_dni,
                              md.$this->field_apellidos as member_apellidos,
                              md.$this->field_nombre as member_nombre,
                              cap.nombre as capacitacion_nombre,
                              md.member_id as member_id,
                              cap.id as capacitacion_id,
                              ins.fecha_inscripcion as fecha_inscripcion,
                              cap.dias_plazo as dias_plazo,
                              cap.presencial as presencial")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->join("member_data md", "md.member_id = ins.member_id or md.member_id = asi.member_id")
                      ->where("md.$this->field_unidad", $unidad);

    if ($capacitacion != "0") {
      $query->where("cap.id", $capacitacion);
    }

    $query->group_by(array("md.member_id", "cap.id"));
    $query = $query->get();
    $rows = $query->result_array();

    foreach ($rows as &$row) {
      $resumen = "verde";

      if ($row['presencial'] != "1") {
        $resultado =  $this->_resultado_capacitacion($row['member_id'], $row['capacitacion_id']);
        $estado = $this->get_estado_capacitacion($row['fecha_inscripcion'], $row['dias_plazo']);

        if ($estado == "finalizada" && ($resultado == "" || $resultado == "desaprobado")) {
          $resumen = "rojo";
        } elseif ($estado == "encurso" && $resultado == "") {
          $resumen = "amarillo";
        }
      }

      $row['resumen'] = $resumen;
      $row['member_nombre'] = $row['member_apellidos'] . ", " . $row['member_nombre'];

      unset($row['member_apellidos']);
      unset($row['member_id']);
      unset($row['capacitacion_id']);
      unset($row['fecha_inscripcion']);
      unset($row['dias_plazo']);
      unset($row['presencial']);
    }

    $headers = array(
      'DNI', "Apellidos y nombre", "Capacitación", "Estado"
    );

    $now = new Datetime('now');
    $filename = "lidermans-capacitaciones-unidad-$unidad-" . $now->getTimestamp();
    Exporter::to_csv($headers, $rows, $filename);
  }

  public function exportar_reporte_asistencia() {
    $unidad = $this->_get_unidad_of_current_member();
    $capacitacion = ee()->TMPL->fetch_param('capacitacion', "0");

    if ($capacitacion == "0") {
      return;
    }

    $html = file_get_contents(self::REPORT_PATH . "reporte_asistencia_template.html");

    $capRow = ee()->db->select("cap.id as capacitacion_id,
                                cap.codigo as capacitacion_codigo,
                                      cap.nombre as capacitacion_nombre,
                                      cap.numero_horas as capacitacion_horas,
                                      cap.capacitador as capacitacion_capacitador,
                                      cap.fecha_inicio as capacitacion_fecha_inicio")
                            ->from("capacitaciones cap")
                            ->where("cap.id", $capacitacion)
                            ->get()->result_array()[0];

    $html = str_replace('{%capacitacion_nombre%}', $capRow['capacitacion_nombre'], $html);
    $html = str_replace('{%capacitacion_duracion%}', $capRow['capacitacion_horas'] . " hora(s)", $html);
    $html = str_replace('{%capacitacion_capacitador%}', $capRow['capacitacion_capacitador'], $html);
    $html = str_replace('{%capacitacion_fecha_inicio%}', date("d/m/Y", strtotime($capRow['capacitacion_fecha_inicio'])), $html);
    $html = str_replace('{%capacitacion_unidad%}', $unidad, $html);

    $query = ee()->db->select("md.$this->field_dni as member_dni,
                              md.$this->field_apellidos as member_apellidos,
                              md.$this->field_nombre as member_nombre,
                              cap.nombre as capacitacion_nombre,
                              md.member_id as member_id,
                              cap.id as capacitacion_id,
                              ins.fecha_inscripcion as fecha_inscripcion,
                              cap.dias_plazo as dias_plazo,
                              cap.presencial as presencial")
                      ->from("capacitaciones cap")
                      ->join("inscripciones ins", "ins.capacitacion_id=cap.id", "left")
                      ->join("asistencias asi", "asi.capacitacion_id=cap.id", "left")
                      ->join("member_data md", "md.member_id = ins.member_id or md.member_id = asi.member_id")
                      ->where("md.$this->field_unidad", $unidad)
                      ->where("cap.id", $capacitacion);

    $query->group_by(array("md.member_id", "cap.id"));
    $query->order_by("md.$this->field_apellidos", "md.$this->field_nombre");
    $query = $query->get();
    $rows = $query->result_array();

    $tableMembers = '<tr align="center">
        <td style="font-weight: bold; font-size: 14px;">NRO.</td>
        <td style="font-weight: bold; font-size: 14px;">APELLIDOS Y NOMBRES</td>
        <td style="font-weight: bold; font-size: 14px;">AREA/UNIDAD</td>
        <td style="font-weight: bold; font-size: 14px;">DNI</td>
        <td style="font-weight: bold; font-size: 14px;">FIRMA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>';
      $idx = 1;

    foreach ($rows as &$row) {
      $resumen = "verde";

      if ($row['presencial'] != "1") {
        $resultado =  $this->_resultado_capacitacion($row['member_id'], $row['capacitacion_id']);
        $estado = $this->get_estado_capacitacion($row['fecha_inscripcion'], $row['dias_plazo']);

        if ($estado == "finalizada" && ($resultado == "" || $resultado == "desaprobado")) {
          $resumen = "rojo";
        } elseif ($estado == "encurso" && $resultado == "") {
          $resumen = "amarillo";
        }
      }

      $row['resumen'] = $resumen;
      $row['member_nombre'] = $row['member_apellidos'] . ", " . $row['member_nombre'];

      if ($resumen == "verde") {
        $tableMembers .= '<tr align="center">
        <td style="font-size: 14px; text-align: center;">' . $idx . '</td>
        <td style="font-size: 14px;">' . $row['member_nombre'] . '</td>
        <td style="font-size: 14px;">' . $unidad . '</td>
        <td style="font-size: 14px;">' . $row['member_dni'] . '</td>
        <td style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>';
        $idx = $idx + 1;
      }
    }

    $html = str_replace('{%table_members%}', $tableMembers, $html);

    $codCap = $capRow['capacitacion_codigo'];
    header("Content-Disposition: attachment; filename=\"reporte-de-control-$unidad-$codCap.pdf\"");
    header("Content-Type: application/pdf; charset=utf-8");
    $mpdf=new mPDF();
    $mpdf->showImageErrors = true;
    $mpdf->WriteHTML($html);
    $content = $mpdf->Output('', 'S');
    echo $content;
    exit;
  }


  private function _get_estado_capacitacion($capacitacion) {
    return $this->get_estado_capacitacion($capacitacion->fecha_inscripcion, $capacitacion->dias_plazo);
  }

  private function get_estado_capacitacion($fecha_inscripcion, $dias_plazo) {
    $dateLimite = DateTime::createFromFormat('Y-m-d', $fecha_inscripcion)
                      ->add(date_interval_create_from_date_string("$dias_plazo days"));
    $dateLimite->setTime(0, 0);
    $dateNow = new DateTime();

    $dentroDelPlazo = $dateNow <= $dateLimite;

    if ($dentroDelPlazo) {
      return "encurso";
    } else {
      return "finalizada";
    }
  }

  private function _get_random_questions($questions, $number) {
    $randomQuestions = array();
    $randomIndices = array_rand($questions, $number);
    foreach ($randomIndices as $indice) {
      $randomQuestions[] = $questions[$indice];
    }
    shuffle($randomQuestions);
    return $randomQuestions;
  }

  private function _get_member_id() {
    return ee()->session->userdata("member_id");
  }

  private function _get_unidad_of_current_member() {
    $member_id = $this->_get_member_id();

    return ee()->db->select("md.$this->field_unidad as unidad")
                    ->from("member_data md")
                    ->where("member_id", $member_id)
                    ->get()->result()[0]->unidad;
  }
  
}