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


}