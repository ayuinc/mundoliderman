<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Capacitaciones {

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
                      ->where("ins.member_id", $member_id)
                      ->or_where("asi.member_id", $member_id)
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
                      ->where("ins.member_id", $member_id)  
                      ->or_where("asi.member_id", $member_id)
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
    $capacitacion_id = ee()->TMPL->fetch_param('capacitacion', FALSE);
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
      $tagdata = ee()->TMPL->tagdata;
      return ee()->TMPL->parse_variables($tagdata, $data);
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
    $countPreguntas = count($preguntas);
    $puntajePregunta = 100.0/$countPreguntas;

    foreach ($preguntas as $pregunta) {
      if (isset($answers[$pregunta["pregunta_id"]]) && $answers[$pregunta["pregunta_id"]][0] == $pregunta["opcion_correcta"]) {
        $puntaje += $puntajePregunta;
      }
    }

    $response = array(
      "puntaje" => $puntaje,
      "estado" => $puntaje >= $porcentajeAprobacion ? "aprobado" : "desaprobado"
    );
    
    echo json_encode($response);
  }

  private function _get_member_id() {
    return ee()->session->userdata("member_id");
  }
  
}