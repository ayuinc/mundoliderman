<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Capacitaciones Helper File
 *
 * @package     Capacitaciones
 * @author      Hugo Angeles http://github.com/hugoangeles0810
 */
class Capacitaciones_helper {
  private $package = 'capacitaciones';

  const TIPO_UNIDAD = "1";
  const UNIDAD = "2";

  const UNIDAD_MINERA = "0";
  const UNIDAD_RETAIL = "1";
  const UNIDAD_PETROLERA = "2";

  // ******************************************************************************** //
  function define_theme_url() {
    if (defined('URL_THIRD_THEMES') === TRUE)
    {
      $theme_url = URL_THIRD_THEMES;
    }
    else
    {
      $theme_url = ee()->config->item('theme_folder_url').'third_party/';
    }

    // Are we working on SSL?
    if (isset($_SERVER['HTTP_REFERER']) == TRUE AND strpos($_SERVER['HTTP_REFERER'], 'https://') !== FALSE)
    {
      $theme_url = str_replace('http://', 'https://', $theme_url);
    }

    if (! defined('CAPACITACIONES_THEME_URL')) define('CAPACITACIONES_THEME_URL', $theme_url . 'capacitaciones/');

    return CAPACITACIONES_THEME_URL;
  }

  // ******************************************************************************** //

  public function get_theme_url()
  {
    $theme_url = trim(ee()->config->item('theme_folder_url').'third_party/' . $this->package . '/');

    if (defined('URL_THIRD_THEMES') === TRUE)
    {
      $theme_url = URL_THIRD_THEMES.$this->package.'/';
    }

    $theme_url = str_replace(array('http://','https://'), '//', $theme_url);

    return $theme_url;
  }

  // ******************************************************************************** //

  public function mcp_js_css($type='', $path='', $package='', $name='', $theme_url=false) {
    $url  = $theme_url ? $theme_url : $this->get_theme_url();
    $url .= $path;

    // CSS
    if ($type == 'css') {
      if (isset(ee()->session->cache['css'][$package][$name]) === FALSE)
      {
        ee()->cp->add_to_foot('<link rel="stylesheet" href="' . $url . '" type="text/css" media="print, projection, screen" />');
        ee()->session->cache['css'][$package][$name] = TRUE;
      }
    }

    // JS
    if ($type == 'js') {
      if (isset(ee()->session->cache['javascript'][$package][$name]) === FALSE)
      {
        ee()->cp->add_to_foot('<script src="' . $url . '" type="text/javascript"></script>');
        ee()->session->cache['javascript'][$package][$name] = TRUE;
      }
    }
  }

  function get_tipo_asignacion_str($tipo_asignacion) {
    if ($tipo_asignacion == self::TIPO_UNIDAD) {
      return "Por Tipo de unidad";
    } else if ($tipo_asignacion == self::UNIDAD) {
      return "Por unidad";
    }

    return "";
  }

  function get_tipo_unidad_str($tipo_unidad) {
    if ($tipo_unidad == self::UNIDAD_MINERA) {
      return "Unidad Minera";
    } else if ($tipo_unidad == self::UNIDAD_RETAIL) {
      return "Unidad Retail";
    } else if ($tipo_unidad == self::UNIDAD_PETROLERA) {
      return "Unidad Petrolera";
    }

    return "";
  }

} // END CLASS

/* End of file capacitaciones_helper.php  */
/* Location: ./system/expressionengine/third_party/capacitaciones/libraries/capacitaciones_helper.php */