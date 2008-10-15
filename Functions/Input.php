<?php

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
