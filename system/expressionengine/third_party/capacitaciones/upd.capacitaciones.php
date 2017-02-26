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

    $fields = array(
      'id' => array('type' => 'int', 'unsigned' => TRUE, 'auto_increment' => TRUE),
      'nombre' => array('type' => 'varchar', 'constraint' => '250', 'null' => FALSE),
      'descripcion' => array('type' => 'text', 'null' => FALSE),
      'fecha_inicio' => array('type' => 'date', 'null' => FALSE),
      'fecha_fin_vigencia' => array('type' => 'date', 'null' => FALSE),
      'fecha_fin_plazo' => array('type' => 'date', 'null' => FALSE)
    );

    ee()->dbforge->add_field($fields);
    ee()->dbforge->add_key('id', TRUE);
    ee()->dbforge->create_table('capacitaciones');
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


    ee()->dbforge->drop_table('capacitaciones');

    return TRUE;
  }

} // END CLASS

/* End of file upd.capacitaciones.php */
/* Location: ./system/expressionengine/third_party/capacitaciones/upd.capacitaciones.php */

