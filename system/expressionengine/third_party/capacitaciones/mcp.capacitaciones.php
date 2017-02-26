<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Capacitaciones Control Panel Class
 *
 * @package     Capacitaciones
 * @author      Hugo Angeles http://github.com/hugoangeles0810
 */
class Capacitaciones_mcp {

  function  __construct() 
  {   

    // Load libraries
    ee()->load->model('capacitacion_model');
    ee()->load->library('capacitaciones_helper');
    ee()->load->helper('form');
    ee()->load->helper('url');

    // Some Globals
    $this->base = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=capacitaciones';
    $this->base_short = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=capacitaciones';
    $this->base_cp = BASE;

    $this->vData = array(); // Global view data array
    $this->vData['base_url'] = $this->base;
    $this->vData['base_url_short'] = $this->base_short;
    $this->vData['base_cp'] = $this->base_cp;

    ee()->capacitaciones_helper->define_theme_url();

    $this->mcp_globals();

    ee()->view->cp_page_title =  lang('capacitaciones_module_name');
  }

  public function index() {
    return $this->listado();
  }

  public function nueva() {
    ee()->view->cp_page_title =  lang('c:nueva');
    $this->vData['section'] = 'nueva';
    $this->vData['action_url'] = $this->base . '&method=guardar';
    return ee()->load->view('mcp/main/nueva', $this->vData, TRUE);
  }

  public function guardar() {

    if ($this->_is_form_capacitacion_valid() == FALSE) {
      return $this->nueva();
    } else {
      ee()->capacitacion_model->save();
      ee()->session->set_flashdata('message_success', lang('c:capacitacion_registrada'));
      ee()->functions->redirect($this->base . '&method=listado');
    }
  }

  public function listado() {
    ee()->view->cp_page_title =  lang('c:listado');
    ee()->load->library('table');

    $this->vData['section'] = 'listado';

    $this->vData['table_capacitaciones'] = ee()->table->datasource('_datasource_capacitaciones');

    return ee()->load->view('mcp/main/listado', $this->vData, TRUE);
  }

  public function nuevo_contenido() {
    $capacitacion_id =  ee()->input->get_post('capacitacion_id', TRUE);
    ee()->capacitacion_model->load($capacitacion_id);

    ee()->view->cp_page_title =  ee()->capacitacion_model->nombre;

    $this->vData['section'] = 'nuevo_contenido';
    $this->vData['action_url'] = $this->base . '&method=guardar_contenido&capacitacion_id=' . $capacitacion_id;
    $this->vData['capacitacion_id'] = $capacitacion_id;
    return ee()->load->view('mcp/capacitacion/nuevo_contenido', $this->vData, TRUE);
  }

  public function guardar_contenido() {
    $capacitacion_id =  ee()->input->get_post('capacitacion_id', TRUE);
    if ($this->_is_form_contenido_valid() == FALSE) {
      return $this->nuevo_contenido();
    } else {
      ee()->session->set_flashdata('message_success', lang('c:contenido_registrado'));
      ee()->functions->redirect($this->base . '&method=contenidos&capacitacion_id=' . $capacitacion_id);
    }
  }

  public function contenidos() {
    $capacitacion_id =  ee()->input->get_post('capacitacion_id', TRUE);
    ee()->capacitacion_model->load($capacitacion_id);
    ee()->view->cp_page_title =  ee()->capacitacion_model->nombre;

    $this->vData['section'] = 'contenidos';
    $this->vData['capacitacion_id'] = $capacitacion_id;
    return ee()->load->view('mcp/capacitacion/contenidos', $this->vData, TRUE);
  }


  // Validación para registrar capacitación
  private function _is_form_capacitacion_valid() {
    ee()->load->library('form_validation');
    ee()->form_validation->set_rules('nombre', 'Nombre', 'required');
    ee()->form_validation->set_rules('fecha_inicio', 'Fecha de inicio', 'required|callback_date_valid');
    ee()->form_validation->set_rules('fecha_fin_vigencia', 'Fecha de fin de vigencia', 'required|callback_date_valid');
    ee()->form_validation->set_rules('fecha_fin_plazo', 'Fecha de fin de plazo', 'required|callback_date_valid');
    ee()->form_validation->set_message('required', lang('c:field_required'));
    ee()->form_validation->set_message('date_valid', lang('c:entry_date_valid'));

    return ee()->form_validation->run();
  }

  // Validación para registrar contenido
  private function _is_form_contenido_valid() {
    ee()->load->library('form_validation');
    ee()->form_validation->set_rules('nombre', 'Nombre', 'required');
    ee()->form_validation->set_message('required', lang('c:field_required'));

    return ee()->form_validation->run();
  }

  function date_valid($date) {
    $day = (int) substr($date, 8, 2);
    $month = (int) substr($date, 5, 2);
    $year = (int) substr($date, 0, 4);
    return checkdate($month, $day, $year);
  }


  // Table datasource de capacitaciones
  function _datasource_capacitaciones($state) {

    $offset = $state['offset'];

    ee()->table->set_columns(array(
        'id'  => array('header' => '#'),
        'nombre'  => array('header' => 'Nombre'),
        'fecha_inicio' => array('header' => 'Fecha Inicio'),
        'fecha_fin_vigencia'  => array('header' => 'Fecha Fin Vigencia'),
        'fecha_fin_plazo'  => array('header' => 'Fecha Fin Plazo'),
        'acciones' => array('header' => 'Acciones'),
    ));

    $rows = ee()->db->select('id, nombre, fecha_inicio, fecha_fin_vigencia, fecha_fin_plazo')
                ->from('capacitaciones')
                ->get()->result_array();

    $rows = array_map(array($this, "_format_row"), $rows);

    return array(
      'rows' => array_slice($rows, $offset, 20),
      'pagination' => array(
        'per_page'   => 20,
        'total_rows' => count($rows),
      ),
    );
  }

  function _format_row($row) {
    $row['nombre'] = '<a href="' . $this->base . '&method=nuevo_contenido&capacitacion_id=' . $row['id']. '">' 
                        . $row['nombre'] . '</a>';
    $row['acciones'] = '<a href="#">Editar</a> | <a href="#">Borrar</a>';
    return $row;
  }


  public function mcp_globals() {
    ee()->cp->set_breadcrumb($this->base, lang('capacitaciones_module_name'));
    ee()->capacitaciones_helper->mcp_js_css('css', 'jquery-ui.min.css', 'jquery-ui', 'main');
    ee()->capacitaciones_helper->mcp_js_css('js', 'jquery-ui.min.js', 'jquery-ui', 'main');
    ee()->capacitaciones_helper->mcp_js_css('css', 'capacitaciones_mcp.css', 'capacitaciones_mcp', 'main');
    ee()->capacitaciones_helper->mcp_js_css('js', 'capacitaciones_mcp.js', 'capacitaciones_mcp', 'main');
  }
  
} // END CLASS

/* End of file mcp.capacitaciones.php */
/* Location: ./system/expressionengine/third_party/capacitaciones/mcp.capacitaciones.php */