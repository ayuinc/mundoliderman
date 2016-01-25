<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parameters {

    var $return_data    = '';

    function value()
    {
    	$code = ee()->TMPL->fetch_param("code");
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


}