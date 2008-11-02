<?php

/**
 * If the script is being run through a browser, this will return the IPv4 address
 * of the client currenly viewing it.
 * @author vmc <vmc@leftnode.com>
 * @retval string Returns the IPv4 address.
 */
function asfw_get_ipv4() {
	$ip = NULL;
	if ( true === isset($_SERVER) ) {
		if ( true === asfw_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( true === asfw_exists('HTTP_CLIENT_IP', $_SERVER)) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	}
	
	return $ip;
}

/**
 * If the script is being run through a browser, this will return the User Agent
 * that the client is currently using.
 * @author vmc <vmc@leftnode.com>
 * @retval string Returns the User Agent string.
 */
function asfw_get_user_agent() {
	if ( true === asfw_exists('HTTP_USER_AGENT', $_SERVER) ) {
		return $_SERVER['HTTP_USER_AGENT'];
	}
	
	return NULL;
}
