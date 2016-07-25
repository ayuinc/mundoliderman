<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parameters {

    const CHATEADORA = "6";
    const ACCESSSGI = "ACCESSSGI";
    const ACCESSIND = "ACCESSIND";
    const ACCESSCHAT = "ACCESSCHAT";

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

    function chateadora_can_view() {
        $memberId = ee()->TMPL->fetch_param("member_id");
        $groupId = $this->get_member_group($memberId);

        $access = $this->_value(self::ACCESSCHAT);
        $groups = explode(',', $access);
        return $this->usuario_actual_es_chateadora() && in_array($groupId, $groups);
    }

    function usuario_actual_es_chateadora() {
        return ee()->session->userdata('group_id') == self::CHATEADORA;
    }

    function get_member_group($memberId) {
        ee()->db->select("group_id")
                    ->from("members")
                    ->where(array("member_id" => $memberId))
                    ->limit(1);

        $result = ee()->db->get()->row();
        if ($result != null) {
            return $result->group_id;
        }

        return null;
    }

}