<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Capacitaciones {

  public function load_capacitaciones() {
    $data = [];

    $member_id = $this->_get_member_id();

    if ($member_id == FALSE) {
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

  private function _get_member_id() {
    return ee()->session->userdata("member_id");
  }
  
}