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

    $this->getCustomMemberFields();

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

  public function editar_capacitacion() {
    $capacitacion_id =  ee()->input->get_post('capacitacion_id', TRUE);
    ee()->capacitacion_model->load($capacitacion_id);

    ee()->view->cp_page_title =  lang('c:editar');

    $this->vData['capacitacion'] =  ee()->capacitacion_model;
    $this->vData['action_url'] = $this->base . '&method=actualizar_capacitacion';
    return ee()->load->view('mcp/main/editar', $this->vData, TRUE);
  }

  public function actualizar_capacitacion() {
    if ($this->_is_form_capacitacion_valid() == FALSE) {
      return $this->editar_capacitacion();
    } else { 
      ee()->capacitacion_model->update();
      ee()->session->set_flashdata('message_success', lang('c:capacitacion_actualizada'));
      ee()->functions->redirect($this->base . '&method=listado');
    }
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
    ee()->load->model('contenido_model');
    $capacitacion_id =  ee()->input->get_post('capacitacion_id', TRUE);
    if ($this->_is_form_contenido_valid() == FALSE) {
      return $this->nuevo_contenido();
    } else {
      ee()->contenido_model->save($capacitacion_id);
      ee()->session->set_flashdata('message_success', lang('c:contenido_registrado'));
      ee()->functions->redirect($this->base . '&method=contenidos&capacitacion_id=' . $capacitacion_id);
    }
  }

  public function contenidos() {
    $capacitacion_id =  ee()->input->get_post('capacitacion_id', TRUE);
    ee()->load->library('table');
    ee()->capacitacion_model->load($capacitacion_id);
    ee()->view->cp_page_title =  ee()->capacitacion_model->nombre;

    $this->vData['section'] = 'contenidos';
    $this->vData['capacitacion_id'] = $capacitacion_id;

    $this->vData['table_contenidos'] = ee()->table->datasource('_datasource_contenidos');

    return ee()->load->view('mcp/capacitacion/contenidos', $this->vData, TRUE);
  }

  public function editar_contenido() {
    ee()->load->model('contenido_model');

    $contenido_id = ee()->input->get('contenido_id', TRUE);
    ee()->contenido_model->load($contenido_id);
    $contenido = ee()->contenido_model;
    ee()->capacitacion_model->load($contenido->capacitacion_id);
    $capacitacion = ee()->capacitacion_model;


    ee()->cp->set_breadcrumb($this->base . '&method=contenidos&capacitacion_id' . $capacitacion->id, $capacitacion->nombre);
    ee()->view->cp_page_title =  lang('c:editar_contenido');


    $this->vData['capacitacion'] = $capacitacion;
    $this->vData['contenido'] = $contenido;
    $this->vData['action_url'] = $this->base . '&method=actualizar_contenido&capacitacion_id=' . $capacitacion_id;


    return ee()->load->view('mcp/capacitacion/editar_contenido', $this->vData, TRUE);
  }

  public function actualizar_contenido() {
    ee()->load->model('contenido_model');

    if ($this->_is_form_contenido_valid() == FALSE) {
      return $this->editar_contenido();
    } else { 
      ee()->contenido_model->update();
      ee()->session->set_flashdata('message_success', lang('c:contenido_actualizado'));
      ee()->functions->redirect($this->base . '&method=contenidos&capacitacion_id=' . ee()->contenido_model->capacitacion_id);
    }
  }

  public function confirmar_eliminar_contenido() {
    ee()->load->model('contenido_model');
    $contenido_id =  ee()->input->get_post('contenido_id', TRUE);
    ee()->contenido_model->load($contenido_id);
    ee()->capacitacion_model->load(ee()->contenido_model->capacitacion_id);

    ee()->cp->set_breadcrumb($this->base . '&method=contenidos&capacitacion_id=' . ee()->capacitacion_model->id, ee()->capacitacion_model->nombre);
    ee()->view->cp_page_title =  lang('c:confirmar_eliminacion');

    $this->vData['contenido'] =  ee()->contenido_model;
    $this->vData['static_url'] = ee()->config->item('s3_path');
    $this->vData['action_url'] = $this->base . '&method=eliminar_contenido&contenido_id=' . $contenido_id;

    return ee()->load->view('mcp/capacitacion/confirmar_eliminar_contenido', $this->vData, TRUE);
  }

  public function eliminar_contenido() {
    ee()->load->model('contenido_model');
    $contenido_id =  ee()->input->post('id', TRUE);
    ee()->contenido_model->load($contenido_id);
    $capacitacion_id = ee()->contenido_model->capacitacion_id;

    ee()->db->where('id', $contenido_id)
            ->delete('contenidos');

    ee()->session->set_flashdata('message_success', lang('c:contenido_eliminado'));
    ee()->functions->redirect($this->base . '&method=contenidos&capacitacion_id=' . $capacitacion_id);
  }

  public function inscripciones() {
    ee()->load->library('table');
    $capacitacion_id = ee()->input->get('capacitacion_id', TRUE);
    ee()->capacitacion_model->load($capacitacion_id);
    $capacitacion =  ee()->capacitacion_model;

    ee()->cp->set_breadcrumb($this->base . '&method=contenidos&capacitacion_id=' . $capacitacion->id, $capacitacion->nombre);

    $this->load_member_fields();

    ee()->view->cp_page_title =  lang('c:inscripciones');

    $this->vData['section'] = 'inscripciones';
    $this->vData['action_url'] = $this->base . '&method=registrar_inscripciones&capacitacion_id=' . $capacitacion_id;
    $this->vData['table_inscripciones'] = ee()->table->datasource('_datasource_inscripciones');

    return ee()->load->view('mcp/capacitacion/inscripciones', $this->vData, TRUE);
  }

  public function registrar_inscripciones() {
    $capacitacion_id = ee()->input->get('capacitacion_id');

    // Usuarios visualizados en el formulario
    $member_form_ids = ee()->input->post('users');

    // Usuarios seleccionados en el formulario
    $member_check_ids = ee()->input->post('toggle');

    // Borrar inscripción de usuarios que no se seleccionaron
    $this->_delete_members_no_enrol($capacitacion_id, $member_form_ids, $member_check_ids);
    // Inscribir usuario seleccionados que no se encuentran en la tabla incriciones
    $this->add_members_no_enrol($capacitacion_id, $member_check_ids, $member_form_ids);

    ee()->session->set_flashdata('message_success', lang('c:inscripciones_actualizadas'));
    ee()->functions->redirect($this->base . '&method=inscripciones&capacitacion_id=' . $capacitacion_id);
  }

  public function ajax_find_unidad() {
    $term = ee()->input->get_post('term', TRUE);

    $this->load_member_fields();

    $data = ee()->db->distinct()->select("$this->field_unidad as name")
                      ->from("member_data")
                      ->like("$this->field_unidad", $term)
                      ->get()->result_array();

    header('Content-Type: application/json');                  
    echo json_encode($data);exit;
  }

  public function ajax_find_zona() {
    $term = ee()->input->get_post('term', TRUE);
    $unidad = ee()->input->get_post('unidad', TRUE);

    $this->load_member_fields();

    $data = ee()->db->distinct()->select("$this->field_zona as name")
                      ->from("member_data")
                      ->where("$this->field_unidad", $unidad)
                      ->like("$this->field_zona", $term)
                      ->get()->result_array();

    header('Content-Type: application/json');                  
    echo json_encode($data);exit;
  }

  // Obtener ids de usuarios inscritos a una capacitación visualizados en el formulario
  private function _get_exists_member_ids($capacitacion_id, $member_form_ids) {
    $rows = ee()->db->select('member_id')
                        ->from('inscripciones')
                        ->where('capacitacion_id', $capacitacion_id)
                        ->where_in('member_id', $member_form_ids)
                        ->get()->result_array();

    $exists_members_ids = array();
    $len = count($rows);
    foreach ($rows as $row) {
      $exists_members_ids[] = $row['member_id'];
    }

    return $exists_members_ids;
  }

  // Borrar inscripción de usuarios que no se seleccionaron 
  private function _delete_members_no_enrol($capacitacion_id, $member_form_ids, $member_check_ids) {
    ee()->db->where('capacitacion_id', $capacitacion_id)
            ->where_in('member_id', $member_form_ids)
            ->where_not_in('member_id', $member_check_ids)
            ->delete('inscripciones');
  }

  private function add_members_no_enrol($capacitacion_id, $member_check_ids, $member_form_ids) {
    // Usuarios ya inscritos en el formulario
    $member_exists_ids = $this->_get_exists_member_ids($capacitacion_id, $member_form_ids);

    foreach ($member_check_ids as $member_check_id) {
      if (!in_array($member_check_id, $member_exists_ids)) {
        ee()->db->insert('inscripciones', array(
          'capacitacion_id' => $capacitacion_id,
          'member_id' => $member_check_id,
          'fecha_inscripcion' => date("Y-m-d", time())
        ));
      }
    }
  }

  // Validación para registrar capacitación
  private function _is_form_capacitacion_valid() {
    ee()->load->library('form_validation');
    ee()->form_validation->set_rules('codigo', 'Código', 'required');
    ee()->form_validation->set_rules('nombre', 'Nombre', 'required');
    ee()->form_validation->set_rules('fecha_inicio', 'Fecha de inicio', 'required|callback_date_valid');
    ee()->form_validation->set_rules('fecha_fin_vigencia', 'Fecha de fin de vigencia', 'required|callback_date_valid');
    ee()->form_validation->set_rules('dias_plazo', 'Días de plazo', 'required|is_natural_no_zero');
    ee()->form_validation->set_message('required', lang('c:field_required'));
    ee()->form_validation->set_message('date_valid', lang('c:entry_date_valid'));
    ee()->form_validation->set_message('is_natural_no_zero', lang('c:natural_no_zero_error'));

    return ee()->form_validation->run();
  }

  // Validación para registrar contenido
  private function _is_form_contenido_valid() {
    ee()->load->library('form_validation');
    ee()->form_validation->set_rules('nombre', 'Nombre', 'required');
    ee()->form_validation->set_rules('orden', 'Orden', 'callback_empty_or_natural_valid');
    ee()->form_validation->set_message('required', lang('c:field_required'));
    ee()->form_validation->set_message('empty_or_natural_valid', lang('c:empty_or_natural'));

    return ee()->form_validation->run();
  }

  function date_valid($date) {
    $day = (int) substr($date, 8, 2);
    $month = (int) substr($date, 5, 2);
    $year = (int) substr($date, 0, 4);
    return checkdate($month, $day, $year);
  }

  function empty_or_natural_valid($number) {
    if ($number == "") {
      return TRUE;
    }

    return ee()->form_validation->is_natural($number);
  }


  // Table datasource de capacitaciones
  function _datasource_capacitaciones($state) {
    $per_page = 20;
    $offset = $state['offset'];

    ee()->table->set_columns(array(
        'codigo' => array('header' => 'Cod.'),
        'nombre'  => array('header' => 'Nombre'),
        'tipo_asignacion' => array('header' => 'Tipo asignación'),
        'tipo_unidad' => array('header' => 'Tipo unidad'),
        'fecha_inicio' => array('header' => 'Fecha Inicio'),
        'fecha_fin_vigencia'  => array('header' => 'Fecha Fin Vigencia'),
        'dias_plazo'  => array('header' => 'Días de plazo'),
        'acciones' => array('header' => 'Acciones'),
    ));

    $rows = ee()->db->select('id, codigo, nombre, tipo_asignacion, tipo_unidad, fecha_inicio, fecha_fin_vigencia, dias_plazo')
                ->from('capacitaciones')
                ->get()->result_array();

    $rows = array_map(array($this, "_format_row_capacitacion"), $rows);

    return array(
      'rows' => array_slice($rows, $offset, $per_page),
      'pagination' => array(
        'per_page'   => $per_page,
        'total_rows' => count($rows),
      ),
    );
  }

  function _format_row_capacitacion($row) {
    $row['tipo_asignacion'] = ee()->capacitaciones_helper->get_tipo_asignacion_str($row['tipo_asignacion']);
    $row['tipo_unidad'] = ee()->capacitaciones_helper->get_tipo_unidad_str($row['tipo_unidad']);
    $row['nombre'] = '<a href="' . $this->base . '&method=contenidos&capacitacion_id=' . $row['id']. '">' 
                        . $row['nombre'] . '</a>';

    $edit_url = $this->base . '&method=editar_capacitacion&capacitacion_id=' . $row['id'];
    $row['acciones'] = '<a href="' . $edit_url . '">Editar</a> | <a href="#">Borrar</a>';
    return $row;
  }
  // Fin Table datasouce de capacitaciones


  // Table datasource de contenidos
  function _datasource_contenidos($state) {
    $capacitacion_id =  ee()->input->get_post('capacitacion_id', TRUE);

    ee()->table->set_columns(array(
        'id'  => array('header' => '#'),
        'nombre'  => array('header' => 'Nombre'),
        'file_path' => array('header' => 'Archivo'),
        'video_id'  => array('header' => 'Video'),
        'acciones' => array('header' => 'Acciones')
    ));

    $rows = ee()->db->select('id, nombre, file_path, video_id')
                ->from('contenidos')
                ->where('capacitacion_id', $capacitacion_id)
                ->get()->result_array();

    $rows = array_map(array($this, "_format_row_contenido"), $rows);

    return array(
      'rows' => $rows
    );
  }

  function _format_row_contenido($row) {
    $s3_path = ee()->config->item('s3_path');
    if ($row['file_path'] != "") {
      $row['file_path'] = '<a href="' . $s3_path . $row['file_path'] . '" target="_blank">Ver Archivo</a>';
    }

    if ($row['video_id'] != "") {
      $row['video_id'] = '<a href="https://www.youtube.com/watch?v=' . $row['video_id'] . '" target="_blank">Ver Video</a>';
    }

    $edit_url = $this->base . '&method=editar_contenido&contenido_id=' . $row['id'];
    $delete_url = $this->base . '&method=confirmar_eliminar_contenido&contenido_id=' . $row['id'];
    $row['acciones'] = '<a href="' . $edit_url . '">Editar</a> | <a href="' . $delete_url . '">Borrar</a>';
    return $row;
  }
  // Fin Table datasource de contenidos

  // Table datasource de contenidos
  public function _datasource_inscripciones($state) {
    $per_page = 20;
    $offset = $state['offset'];
    $codigo = ee()->input->post('codigo', TRUE);
    $dni = ee()->input->post('dni', TRUE);
    $nombre = ee()->input->post('nombre', TRUE);
    $apellidos = ee()->input->post('apellidos', TRUE);
    $unidad = ee()->input->post('unidad', TRUE);
    $zona = ee()->input->post('zona', TRUE);

    ee()->table->set_columns(array(
      'codigo'  => array('header' => 'Cod.'),
      'dni'  => array('header' => 'DNI'),
      'nombre'  => array('header' => 'Nombre'),
      'apellidos'  => array('header' => 'Apellidos'),
      'unidad'  => array('header' => 'Unidad'),
      'zona' => array('header' => 'Zona'),
      'check' => array('header' => '<input type="checkbox" name="select_all" value="true" class="toggle_all">', 'sort' => FALSE)
    ));


    $query = ee()->db->select(
                      "m.member_id as member_id, " . 
                      "md.$this->field_codigo as codigo, " .
                      "md.$this->field_dni as dni, " .
                      "md.$this->field_nombre as nombre, " . 
                      "md.$this->field_apellidos as apellidos, " . 
                      "md.$this->field_unidad as unidad, " . 
                      "md.$this->field_zona as zona, " . 
                      "ins.id as checked")
                    ->from("members m")
                    ->join("member_data md", "md.member_id = m.member_id")
                    ->join("inscripciones ins", "m.member_id = ins.member_id", "left");

    if ($unidad !== FALSE) {
       $query = $query->where("md.$this->field_unidad", $unidad);
    }

    if ($zona !== FALSE) {
       $query = $query->where("md.$this->field_zona", $zona);
    }

    if ($codigo !== FALSE) {
      $query = $query->like("md.$this->field_codigo", $codigo);
    }

    if ($dni !== FALSE) {
      $query = $query->like("md.$this->field_dni", $dni);
    }

    if ($nombre !== FALSE) {
      $query = $query->like("md.$this->field_nombre", $nombre);
    }

    if ($apellidos !== FALSE) {
      $query = $query->like("md.$this->field_apellidos", $apellidos);
    }

    $query = $query->limit($per_page, $offset);
    $rows = $query->get()->result_array();
    $rows = array_map(array($this, "_format_row_inscripciones"), $rows);

    $query = ee()->db->from("members m")
                    ->join("member_data md", "md.member_id = m.member_id")
                    ->join("inscripciones ins", "m.member_id = ins.member_id", "left");

    if ($unidad !== FALSE) {
       $query = $query->where("md.$this->field_unidad", $unidad);
    }

    if ($zona !== FALSE) {
       $query = $query->where("md.$this->field_zona", $zona);
    }

    if ($codigo !== FALSE) {
      $query = $query->like("md.$this->field_codigo", $codigo);
    }

    if ($dni !== FALSE) {
      $query = $query->like("md.$this->field_dni", $dni);
    }

    if ($nombre !== FALSE) {
      $query = $query->like("md.$this->field_nombre", $nombre);
    }

    if ($apellidos !== FALSE) {
      $query = $query->like("md.$this->field_apellidos", $apellidos);
    }

    $count = $query->count_all_results();


    return array(
      'rows' => $rows,
      'pagination' => array(
        'per_page'   => $per_page,
        'total_rows' => $count
      ),
    );
  }

  function _format_row_inscripciones($row) {
    $checked = is_null($row['checked']) ? '' : 'checked';
    $row['check'] = '<input class="toggle" type="checkbox" name="toggle[]" value="' . $row['member_id'] . '" data-is="' . $checked . '" ' . $checked . ' >' .
      '<input type="hidden" name="users[]" value="' . $row['member_id'] .'">';
    return $row;
  }
  // Fin Table datasource de contenidos

  public function mcp_globals() {
    ee()->cp->set_breadcrumb($this->base, lang('capacitaciones_module_name'));
    ee()->capacitaciones_helper->mcp_js_css('css', 'jquery-ui.min.css', 'jquery-ui', 'main');
    ee()->capacitaciones_helper->mcp_js_css('js', 'jquery-ui.min.js', 'jquery-ui', 'main');
    ee()->capacitaciones_helper->mcp_js_css('css', 'capacitaciones_mcp.css', 'capacitaciones_mcp', 'main');
    ee()->capacitaciones_helper->mcp_js_css('js', 'capacitaciones_mcp.js', 'capacitaciones_mcp', 'main');
  }

  private function load_member_fields() {
    $this->field_nombre = $this->getMemberFieldId('nombres');
    $this->field_apellidos = $this->getMemberFieldId('apellidos');
    $this->field_unidad = $this->getMemberFieldId('unidad');
    $this->field_zona = $this->getMemberFieldId('zona');
    $this->field_codigo = $this->getMemberFieldId('codigo-liderman');
    $this->field_dni = $this->getMemberFieldId('dni');
    $this->field_tipo_usuario = $this->getMemberFieldId('tipo-usuario');
  }

  private function getCustomMemberFields() {
    $member_fields = ee()->db->where('m_field_reg', 'y')
             ->get("member_fields");
    $this->custom_fields = $member_fields->result_array();
  }

  private function getMemberFieldId($field_name) {
    $field_id = 0;
    foreach ($this->custom_fields as $key => $value) {
      if ($field_name == $value["m_field_name"]) {
        $field_id = $value["m_field_id"];
        break;
      }
    }
    return 'm_field_id_' . $field_id;
  }
  
} // END CLASS

/* End of file mcp.capacitaciones.php */
/* Location: ./system/expressionengine/third_party/capacitaciones/mcp.capacitaciones.php */