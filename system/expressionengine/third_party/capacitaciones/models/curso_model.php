<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Curso_model extends CI_Model {

  private $table = 'cursos';


  var $id;
  var $codigo;
  var $nombre;

  function __construct() {
      parent::__construct();
  }

  function save() {
    $this->codigo = ee()->input->post('codigo');
    $this->nombre = ee()->input->post('nombre');

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
    }
  }

  function getAll() {
    return ee()->db->get($this->table)->result();
  }

  function update() {
    $id = ee()->input->post('id', TRUE);
    $this->load($id);

    $this->codigo = ee()->input->post('codigo');
    $this->nombre = ee()->input->post('nombre');

    ee()->db->where("id", $this->id);
    ee()->db->update($this->table, $this);
  }

}