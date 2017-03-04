<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pregunta_model extends CI_Model {

  private $table = 'preguntas';
  private $table_opciones = 'pregunta_opciones';

  var $id;
  var $capacitacion_id;
  var $nombre;

  function __construct() {
      parent::__construct();
  }

  function save($capacitacion_id) {
    $this->capacitacion_id = $capacitacion_id;
    $this->nombre = ee()->input->post('nombre');

    ee()->db->insert($this->table, $this);
    $this->id = ee()->db->insert_id();

    $opciones = ee()->input->post('opciones');
    $respuesta = ee()->input->post('respuesta');
    $len = count($opciones);
    for ($i=0; $i < $len; $i++) { 
      $data = array(
        'pregunta_id' => $this->id,
        'nombre' => $opciones[$i]
      );

      if ($i == $respuesta) {
        $data['es_respuesta'] = '1';
      }

      ee()->db->insert($this->table_opciones, $data);
    }

  }

  function update() {
    $id = ee()->input->post('id', TRUE);

    ee()->db->where('id', $id)
              ->update($this->table, array(
                'nombre' => ee()->input->post('nombre')
              ));

    $opciones = ee()->input->post('opciones');
    $ids = ee()->input->post('ids');
    $respuesta = ee()->input->post('respuesta');

    // Borrando opciones eliminadas del formulario
    ee()->db->where('pregunta_id', $id) 
            ->where_not_in('id', $ids)
            ->delete($this->table_opciones);

    // Reestableciendo opcion respuesta
    ee()->db->where('pregunta_id', $id)
            ->update($this->table_opciones, array(
                                'es_respuesta' => '0'
                              ));

    $len = count($opciones);
    for ($i=0; $i < $len ; $i++) { 
      $data = array(
        'pregunta_id' => $id,
        'nombre' => $opciones[$i]
      );

      if ($i == $respuesta) {
        $data['es_respuesta'] = '1';
      }

      if ($ids[$i] == '') { // Insert Pregunta Opcion
         ee()->db->insert($this->table_opciones, $data);
      } else { // Update Pregunta Opcion
        ee()->db->where('id', $ids[$i])
                  ->update($this->table_opciones, $data);
      }
    }
  }

  function load($id) {
    $query = ee()->db->where('id', $id)
                      ->get($this->table);

    if ($query->num_rows > 0) {
      $data = array_shift($query->result_array());


      $this->id = $data['id'];
      $this->capacitacion_id = $data['capacitacion_id'];
      $this->nombre = $data['nombre'];

      $this->opciones = ee()->db->where('pregunta_id', $this->id)
                        ->get($this->table_opciones)->result();
    }
  }


}