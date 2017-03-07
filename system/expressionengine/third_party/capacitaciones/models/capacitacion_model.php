<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Capacitacion_model extends CI_Model {

  private $table = 'capacitaciones';

  const TIPO_UNIDAD = "1";
  const UNIDAD = "2";

  const UNIDAD_MINERA = "0";
  const UNIDAD_RETAIL = "1";
  const UNIDAD_PETROLERA = "2";

  var $id;
  var $codigo;
  var $nombre;
  var $descripcion;
  var $fecha_inicio;
  var $fecha_fin_vigencia;
  var $dias_plazo;
  var $tipo_asignacion;
  var $tipo_unidad;
  var $presencial;

  function __construct() {
      parent::__construct();
  }

  function save() {
    $this->codigo = ee()->input->post('codigo');
    $this->nombre = ee()->input->post('nombre');
    $this->descripcion = ee()->input->post('descripcion');
    $this->fecha_inicio = ee()->input->post('fecha_inicio');
    $this->fecha_fin_vigencia = ee()->input->post('fecha_fin_vigencia');
    $this->dias_plazo = ee()->input->post('dias_plazo');
    $this->tipo_asignacion = ee()->input->post('tipo_asignacion');

    if ($this->tipo_asignacion == self::TIPO_UNIDAD ) {
      $this->tipo_unidad = ee()->input->post('tipo_unidad');
    } else {
      $this->tipo_unidad = NULL;
    }

    if (ee()->input->post('presencial') == FALSE) {
      $this->presencial = 0;
    } else {
      $this->presencial = ee()->input->post('presencial');
    }


    ee()->db->insert($this->table, $this);
  }

  function load($id) {
    $query = ee()->db->where('id', $id)
                      ->get($this->table);

    if ($query->num_rows > 0) {
      $data = array_shift($query->result_array());


      $this->id = $data['id'];
      $this->codigo = $data['codigo'];
      $this->nombre = $data['nombre'];
      $this->descripcion = $data['descripcion'];
      $this->fecha_inicio = $data['fecha_inicio'];
      $this->fecha_fin_vigencia = $data['fecha_fin_vigencia'];
      $this->dias_plazo = $data['dias_plazo'];
      $this->tipo_asignacion = $data['tipo_asignacion'];
      $this->tipo_unidad = $data['tipo_unidad'];
      $this->presencial = $data['presencial'];
    }
  }

  function update() {
    $id = ee()->input->post('id', TRUE);
    $this->load($id);

    $this->codigo = ee()->input->post('codigo');
    $this->nombre = ee()->input->post('nombre');
    $this->descripcion = ee()->input->post('descripcion');
    $this->fecha_inicio = ee()->input->post('fecha_inicio');
    $this->fecha_fin_vigencia = ee()->input->post('fecha_fin_vigencia');
    $this->dias_plazo = ee()->input->post('dias_plazo');
    $this->tipo_asignacion = ee()->input->post('tipo_asignacion');

    if ($this->tipo_asignacion == self::TIPO_UNIDAD ) {
      $this->tipo_unidad = ee()->input->post('tipo_unidad');
    } else {
      $this->tipo_unidad = NULL;
    }

    if (ee()->input->post('presencial') == FALSE) {
      $this->presencial = 0;
    } else {
      $this->presencial = ee()->input->post('presencial');
    }

    ee()->db->where("id", $this->id);
    ee()->db->update($this->table, $this);
  }

  function _format_date($originalDate) {
    return date("Y/m/d", strtotime($originalDate));
  }


  function getFInicioFormated() {
    return $this->_format_date($this->fecha_inicio);
  }

  function getFFinVigenciaFormated() {
    return $this->_format_date($this->fecha_fin_vigencia);
  }

  function getTipoAsignacionStr() {
    if ($this->tipo_asignacion == self::TIPO_UNIDAD) {
      return "Por Tipo de unidad";
    } else if ($this->tipo_asignacion == self::UNIDAD) {
      return "Por unidad";
    }

    return "";
  }

  function getTipoUnidadStr() {
    if ($this->tipo_unidad == self::UNIDAD_MINERA) {
      return "Unidad Minera";
    } else if ($this->tipo_unidad == self::UNIDAD_RETAIL) {
      return "Unidad Retail";
    } else if ($this->tipo_unidad == self::UNIDAD_PETROLERA) {
      return "Unidad Petrolera";
    }

    return "";
  }
}