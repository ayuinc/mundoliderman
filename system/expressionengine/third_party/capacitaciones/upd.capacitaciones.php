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

    // Creando Tabla Capacitaciones
    $fields = array(
      'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
      'nombre' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
      'codigo' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
      'descripcion' => array('type' => 'text', 'null' => FALSE),
      'fecha_inicio' => array('type' => 'date', 'null' => FALSE),
      'fecha_fin_vigencia' => array('type' => 'date', 'null' => FALSE),
      'dias_plazo' => array('type' => 'int', 'null' => FALSE),
      'tipo_asignacion' => array('type' => 'int', 'null' => FALSE),
      'tipo_unidad' => array('type' => 'int', 'null' => TRUE)
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

    ee()->db->where("module_name", $this->module_name);
    ee()->db->delete("modules");

    // Borrando Tabla Contenidos
    ee()->dbforge->drop_table('contenidos');

    // Borrando Tabla Capacitaciones
    ee()->dbforge->drop_table('capacitaciones');

    // Borrando Tabla Inscripciones
    ee()->dbforge->drop_table('inscripciones');

    return TRUE;
  }

} // END CLASS

/* End of file upd.capacitaciones.php */
/* Location: ./system/expressionengine/third_party/capacitaciones/upd.capacitaciones.php */

