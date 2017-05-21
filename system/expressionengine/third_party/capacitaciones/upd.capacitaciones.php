<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Install / Uninstall and updates the modules
 *
 * @package     Capacitaciones
 * @author      Hugo Angeles http://github.com/hugoangeles0810
 */
class Capacitaciones_upd {

  var $version = "1.0";

  private $module_name = "Capacitaciones";

  /**
  * Install the module
  *
  * @return boolean TRUE
  */
  public function install() {

    ee()->load->dbforge();

    $mod_data = array(
        'module_name' => $this->module_name,
        'module_version' => $this->version,
        'has_cp_backend' => 'y',
        'has_publish_fields' => 'n'
    );

    ee()->db->insert('modules', $mod_data);

    // Creando Tabla Cursos
    $fields = array(
      'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
      'nombre' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
      'codigo' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE)
    );
    ee()->dbforge->add_field($fields);
    ee()->dbforge->add_key('id', TRUE);
    ee()->dbforge->create_table('cursos');
    unset($fields);

    // Creando Tabla Capacitaciones
    $fields = array(
      'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
      'nombre' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
      'codigo' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
      'descripcion' => array('type' => 'text', 'null' => FALSE),
      'fecha_inicio' => array('type' => 'date', 'null' => FALSE),
      'fecha_fin_vigencia' => array('type' => 'date', 'null' => FALSE),
      'dias_plazo' => array('type' => 'int', 'null' => FALSE),
      'porcentaje_aprobacion' => array('type' => 'decimal', 'constraint' => '10,2', 'null' => FALSE),
      'numero_horas' => array('type' => 'decimal', 'constraint' => '10,2', 'null' => FALSE),
      'tipo_asignacion' => array('type' => 'int', 'null' => FALSE),
      'tipo_unidad' => array('type' => 'int', 'null' => TRUE),
      'presencial' => array('type' => 'tinyint', 'constraint' => '1','unsigned' => TRUE, 'null' => FALSE, 'default' => '0'),
      'curso_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE),
      'cant_preguntas' => array('type' => 'int', 'null' => FALSE),
      'capacitador' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE, 'default' => '')
    );
    ee()->dbforge->add_field($fields);
    ee()->dbforge->add_key('id', TRUE);
    ee()->dbforge->create_table('capacitaciones');
    unset($fields);


    // Creando Tabla Contenidos
    $fields = array(
      'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
      'capacitacion_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE),
      'nombre' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
      'descripcion' => array('type' => 'text', 'null' => TRUE),
      'file_path' => array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE),
      'video_id' => array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE),
      'orden' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE, 'default' => '0')
    );
    ee()->dbforge->add_field($fields);
    ee()->dbforge->add_key('id', TRUE);
    ee()->dbforge->create_table('contenidos');
    unset($fields);

    // Crenado Tabla Inscripciones
    $fields = array(
      'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
      'capacitacion_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE),
      'member_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE),
      'fecha_inscripcion' => array('type' => 'date', 'null' => FALSE)
    );
    ee()->dbforge->add_field($fields);
    ee()->dbforge->add_key('id', TRUE);
    ee()->dbforge->create_table('inscripciones');
    unset($fields);

    // Crenado Tabla Asistencias
    $fields = array(
      'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
      'capacitacion_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE),
      'member_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE),
      'fecha_asistencia' => array('type' => 'date', 'null' => FALSE)
    );
    ee()->dbforge->add_field($fields);
    ee()->dbforge->add_key('id', TRUE);
    ee()->dbforge->create_table('asistencias');
    unset($fields);

    // Crenado Tabla Pregunta
    $fields = array(
      'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
      'capacitacion_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE),
      'nombre' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE)
    );
    ee()->dbforge->add_field($fields);
    ee()->dbforge->add_key('id', TRUE);
    ee()->dbforge->create_table('preguntas');
    unset($fields);

    // Crenado Tabla pregunta_opciones
    $fields = array(
      'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
      'pregunta_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE),
      'nombre' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
      'es_respuesta' => array('type' => 'tinyint', 'constraint' => '1','unsigned' => TRUE, 'null' => FALSE, 'default' => '0')
    );
    ee()->dbforge->add_field($fields);
    ee()->dbforge->add_key('id', TRUE);
    ee()->dbforge->create_table('pregunta_opciones');
    unset($fields);

    // Creando Tabla test_resultados
    $fields = array(
      'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
      'capacitacion_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE),
      'member_id' => array('type' => 'int', 'unsigned' => TRUE, 'null' => FALSE),
      'puntaje' => array('type' => 'decimal', 'constraint' => '10,2', 'null' => FALSE),
      'porcentaje_aprobacion' => array('type' => 'decimal', 'constraint' => '10,2', 'null' => FALSE),
      'fecha' => array('type' => 'datetime', 'null' => FALSE),
      'estado' => array('type' => 'char', 'constraint' => '1', 'null' => FALSE)
    );
    ee()->dbforge->add_field($fields);
    ee()->dbforge->add_key('id', TRUE);
    ee()->dbforge->create_table('test_resultados');
    unset($fields);

    $data = array(
      array(
        "class" => $this->module_name,
        "method" => "test_post",
        "csrf_exempt" => "1"
      )
    );

    ee()->db->insert_batch("actions", $data);

    return TRUE;
  }

  /**
  * Update the module
  *
  * @return boolean
  */
  public function update($current = "") {
    if ($current == $this->version) {
      // No updates
      return FALSE;
    }

    return TRUE;
  }

  /** 
  * Uninstall the module
  *
  * @return boolean TRUE
  */
  public function uninstall() {
    ee()->load->dbforge();

    ee()->db->where("class", $this->module_name);
    ee()->db->delete("actions");

    ee()->db->where("class", $this->module_name."_mcp");
    ee()->db->delete("actions");

    ee()->db->where("module_name", $this->module_name);
    ee()->db->delete("modules");

    // Borrando Tabla Pregunta
    ee()->dbforge->drop_table('preguntas');

    // Borrando Tabla Pregunta Opciones
    ee()->dbforge->drop_table('pregunta_opciones');

    // Borrando Tabla Asistencias
    ee()->dbforge->drop_table('asistencias');

    // Borrando Tabla Inscripciones
    ee()->dbforge->drop_table('inscripciones');

    // Borrando Tabla Contenidos
    ee()->dbforge->drop_table('contenidos');

    // Borrando Tabla Capacitaciones
    ee()->dbforge->drop_table('capacitaciones');

    // Borrando Tabla Cursos
    ee()->dbforge->drop_table('cursos');

    

    return TRUE;
  }

} // END CLASS

/* End of file upd.capacitaciones.php */
/* Location: ./system/expressionengine/third_party/capacitaciones/upd.capacitaciones.php */

