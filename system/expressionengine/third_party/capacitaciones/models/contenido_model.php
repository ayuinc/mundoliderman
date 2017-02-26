<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Contenido_model extends CI_Model {

  private $table = 'contenidos';

  var $id;
  var $capacitacion_id;
  var $nombre;
  var $descripcion;
  var $file_path;
  var $video_id;
  var $orden;

  function __construct() {
      parent::__construct();
  }

  function save($capacitacion_id) {
    $this->capacitacion_id = $capacitacion_id;
    $this->nombre = ee()->input->post('nombre');
    $this->descripcion = ee()->input->post('descripcion');
    $this->video_id = ee()->input->post('video_id');
    $this->orden = ee()->input->post('orden');

    ee()->db->insert($this->table, $this);
    $this->id = ee()->db->insert_id();
    $file_path = $this->_upload_file();
    $file_data = array(
      "file_path" => $file_path
    );
    ee()->db->where("id", $this->id);
    ee()->db->update($this->table, $file_data);
  }

  function load($contenido_id) {
    $query = ee()->db->where('id', $contenido_id)
                      ->get($this->table);


    if ($query->num_rows > 0) {
      $data = array_shift($query->result_array());

      $this->id = $data['id'];
      $this->capacitacion_id = $data['capacitacion_id'];
      $this->nombre = $data['nombre'];
      $this->descripcion = $data['descripcion'];
      $this->file_path = $data['file_path'];
      $this->video_id = $data['video_id'];
      $this->orden = $data['orden'];
    }
  }

  function update() {
    $id = ee()->input->post('id', TRUE);
    $this->load($id);

    $this->nombre = ee()->input->post('nombre');
    $this->descripcion = ee()->input->post('descripcion');
    $this->video_id = ee()->input->post('video_id');
    $this->orden = ee()->input->post('orden');

    $file_path = $this->_upload_file();

    if ($file_path != "") {
      $this->file_path = $file_path;
    }

    ee()->db->where("id", $this->id);
    ee()->db->update($this->table, $this);
  }

  private function _upload_file() {
    $path = ee()->config->item("capacitaciones_files_path") . $this->capacitacion_id . "/";
    $upload_config['file_name'] = "contenido-" . $this->id . "-" . (isset($_FILES["archivo"]) ? $_FILES["archivo"]['name'] : "");
    $upload_config['allowed_types'] = '*';
    $upload_config['upload_path'] = "/" . $path;
    $upload_config['ignore_path_exists'] = TRUE;
    $upload_config['overwrite']   = TRUE;

    ee()->load->library('upload', $upload_config);
    if ( ! ee()->upload->do_upload_to_s3("archivo")) {
        $error = array('error' => ee()->upload->display_errors());
        return "";
    } else {
      $file_path = $path . ee()->upload->data("file_name")["file_name"];
      return $file_path;
    } 
  }

}