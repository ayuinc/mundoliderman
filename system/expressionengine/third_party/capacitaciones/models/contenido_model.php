<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Contenido_model extends CI_Model {

  private $table = 'contenidos';

  var $id;
  var $nombre;
  var $descripcion;
  var $file_path;
  var $video_id;

  function __construct() {
      parent::__construct();
  }

}