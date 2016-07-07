<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parameters {

    const ACCESSSGI = "ACCESSSGI";
    const ACCESSIND = "ACCESSIND";

    function value()
    {
    	$code = ee()->TMPL->fetch_param("code");
    	return $this->_value($code);
    }

    function _value($code) {
        ee()->db->select('value')
                ->from('parameter')
                ->where(array('code' => $code))
                ->limit(1);

        $result = ee()->db->get()->row();

        if ($result != null) {
            return $result->value;
        }

        return null;
    }

    function can_access_sgi() {
        return $this->_can_access(self::ACCESSSGI);
    }

    function can_access_indicators() {
        return $this->_can_access(self::ACCESSIND);
    }

    function _can_access($code) {
        $access = $this->_value($code);
        $group_id = $group_id = ee()->session->userdata('group_id');
        $groups = explode(',', $access);
        return in_array($group_id, $groups);
    }

}