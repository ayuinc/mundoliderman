<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User {
  var $correo;
  var $username;
  var $tipo_usuario;
  var $zona;
  var $apellido;
  var $nombre;
  var $edad;
  var $dni;
  var $empresa_empleadora;
  var $codigo_liderman;
  var $periodo_planilla;
  var $sexo;
  var $unidad;

  static $custom_fields;

  public function __construct(&$user) {
    if (!isset(self::$custom_fields)) {
      self::$custom_fields = self::getCustomMemberFields();
    }
    try {
      // En el campo CorreoElectronico vienen los campos correo#username#zona#tipoUsuario
      $fieldsStr = htmlspecialchars_decode($user["CorreoElectronico"]);
      if ($fieldsStr == "") {
        return;
      } 

      $fields = split("#", $fieldsStr);

      if (count($fields) == 4) {
        $this->correo = $fields[0];
        $this->username = $fields[1];
        $this->zona = $fields[2];
        $this->tipo_usuario = $fields[3];
      }

      // Nombre completo en formato APELLIDOS, NOMBRES
      $fields = split(",", $user["NombreCompleto"]);
      $this->apellido = trim($fields[0]);
      $this->nombre = trim($fields[1]);

      $this->edad = $user["Edad"];
      $this->dni = trim($user["Documento"]);
      $this->empresa_empleadora = trim($user["Cliente"]);

      $this->codigo_liderman = $user["Liderman"];

      $fechaIngreso = new DateTime(stripslashes($user["FechaIngreso"]));
      $fechaActual = new DateTime();

      $fechaDiff = $fechaIngreso->diff($fechaActual);

      $periodoNum = ($fechaDiff->y * 12) + $fechaDiff->m;
      $this->periodo_planilla = strval($periodoNum);

      $this->sexo = $user["Sexo"];
      $this->unidad = $user["Unidad"];
    } catch (Exception $e) {
      ee()->logger->developer('Jobs: Error: ' . $e->getMessage());
    }

  }

  public function get_member_id() {
    $query = ee()->db->where("username", $this->username)
           ->get("members");

    if ($query->num_rows() > 0) {
      return $query->row("member_id");
    }

    return null;
  }

  public function get_member_data_array() {
    return array(
      self::getMemberFieldId("apellidos") => $this->apellido,
      self::getMemberFieldId("nombres") => $this->nombre,
      self::getMemberFieldId("empresa-empleadora") => $this->empresa_empleadora,
      self::getMemberFieldId('codigo-liderman') => $this->codigo_liderman,
      self::getMemberFieldId('email-perfil') => $this->correo,
      self::getMemberFieldId('tipo-usuario') => $this->tipo_usuario,
      self::getMemberFieldId('periodo-planilla') => $this->periodo_planilla,
      self::getMemberFieldId('edad') => $this->edad,
      self::getMemberFieldId('zona') => $this->zona,
      self::getMemberFieldId('sexo') => $this->sexo,
      self::getMemberFieldId('unidad') => $this->unidad
    );
  }

  public function get_new_user_array() {
    $hash = ee()->auth->hash_password($this->username);
    $space_position = strpos(strval($this->nombre), ' ', 1);
    $member_data = array(
        'username' => $this->username,
        'password'    => $hash["password"],
        'salt'      => $hash["salt"],
        'ip_address'  => ee()->input->ip_address(),
        'unique_id'   => ee()->functions->random('encrypt'),
        'join_date'   => ee()->localize->now,
        'email'     => $this->correo,
        'screen_name' => ($space_position > 0) ? substr(strval($this->nombre), 0, $space_position) : strval($this->nombre),
        'url'     => prep_url(ee()->input->post('url')),
        'location'    => ee()->input->post('location'),

        // overridden below if used as optional fields
        'language'    => (ee()->config->item('deft_lang')) ?
                    ee()->config->item('deft_lang') : 'english',
        'date_format' => ee()->config->item('date_format') ?
                    ee()->config->item('date_format') : '%n/%j/%y',
        'time_format' => ee()->config->item('time_format') ?
                    ee()->config->item('time_format') : '12',
        'include_seconds' => ee()->config->item('include_seconds') ?
                    ee()->config->item('include_seconds') : 'n',
        'timezone'    => ee()->config->item('default_site_timezone')
      );

    $employee_category =$this->cleanCategoryCode($this->tipo_usuario);
    $profiles = ee()->db->query("SELECT group_id from exp_profiles WHERE '$employee_category' = (SELECT REPLACE(`code`, ' ', ''))");

    $group_id = $profiles->row("group_id");
    $member_data['group_id'] = isset($group_id) ? $group_id : ee()->config->item('default_member_group');

    return $member_data;

  }

  private static function getCustomMemberFields()
  {
    $member_fields = ee()->db->where('m_field_reg', 'y')
             ->get("member_fields");
    return $member_fields->result_array();
  }

  private static function getMemberFieldId($field_name)
  {
    $field_id = 0;
    foreach (self::$custom_fields as $key => $value) {
      if ($field_name == $value["m_field_name"]) {
        $field_id = $value["m_field_id"];
        break;
      }
    }
    return 'm_field_id_' . $field_id;
  }

  private function cleanCategoryCode($code) {
    return str_replace(" ", "", $code);
  }
}