<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Capacitacion_model extends CI_Model {

  private $table = 'capacitaciones';

  var $id;
  var $nombre;
  var $descripcion;
  var $fecha_inicio;
  var $fecha_fin_vigencia;
  var $fecha_fin_plazo;

  function __construct() {
      parent::__construct();
  }

  function save() {
    $this->nombre = ee()->input->post('nombre');
    $this->descripcion = ee()->input->post('descripcion');
    $this->fecha_inicio = ee()->input->post('fecha_inicio');
    $this->fecha_fin_vigencia = ee()->input->post('fecha_fin_vigencia');
    $this->fecha_fin_plazo = ee()->input->post('fecha_fin_plazo');

    ee()->db->insert($this->table, $this);
  }

  function load($id) {
    $query = ee()->db->where('id', $id)
                      ->get($this->table);

    if ($query->num_rows > 0) {
      $data = array_shift($query->result_array());

      $this->id = $data['id'];
      $this->nombre = $data['nombre'];
      $this->descripcion = $data['descripcion'];
      $this->fecha_inicio = $data['fecha_inicio'];
      $this->fecha_fin_vigencia = $data['fecha_fin_vigencia'];
      $this->fecha_fin_plazo = $data['fecha_fin_plazo'];
    }
  }

  function update() {
    $id = ee()->input->post('id', TRUE);
    $this->load($id);

    $this->nombre = ee()->input->post('nombre');
    $this->descripcion = ee()->input->post('descripcion');
    $this->fecha_inicio = ee()->input->post('fecha_inicio');
    $this->fecha_fin_vigencia = ee()->input->post('fecha_fin_vigencia');
    $this->fecha_fin_plazo = ee()->input->post('fecha_fin_plazo');

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

  function getFFinPlazoFormated() {
    return $this->_format_date($this->fecha_fin_plazo);
  }

}