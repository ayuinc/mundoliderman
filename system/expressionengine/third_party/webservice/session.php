<?php

class SessionManager  
{
	
	public static function sessionStart($name, $limit = 0, $path = '/', $domain = null, $secure = null)
	{
		// Set the cookie name before starting
		session_name($name . '_Session');

		// Set the domain to default to the current domain
		$domain = isset($domain) ? $domain : isset($_SERVER['SERVER_NAME']);

		// Set the default secure value to whether the site is being accessed with SSL
		$https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);

		// Set the cookie settings and start the session
		session_set_cookie_params($limit, $path, $domain, $secure, true);
		session_start();
	}

}