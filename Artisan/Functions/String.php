<?php

/**
 * Returns the first letter of a string.
 * @author vmc <vmc@leftnode.com>
 * @retval string The first letter of the string.
 */
function asfw_first_letter($word) {
	$w = NULL;
	$word = trim($word);
	if ( false === empty($word) ) {
		$w = $word[0];
	}
	return $w;
}

/**
 * Renames a controller string to be safe for PHP execution.
 * @author vmc <vmc@leftnode.com>
 * @param $controller The name of the controller to rename.
 * @retval string The new safe controller name.
*/
function asfw_rename_controller($controller) {
	$controller = strtolower(trim($controller));
	
	// Strip out non-ascii characters
	$controller = preg_replace('/[^a-z_0-9]/i', NULL, $controller);
	$controller = str_replace('_', ' ', $controller);
	$controller = ucwords($controller);
	$controller = str_replace(' ', '_', $controller);
	
	return $controller;
}

/**
 * Renames a method in a controller, all lowercase with underscores for method names.
 * @author vmc <vmc@leftnode.com>
 * @param $method The name of the method to rename.
 * @retval string The new method name.
 */
function asfw_rename_controller_method($method) {
	$method = preg_replace('/[^a-z_0-9]/i', NULL, $method);
	return strtolower($method);
}

/**
 * Removes any of the query string data from the request_uri to build
 * the controller/method/arguments heirarchy.
 * @author vmc <vmc@leftnode.com>
 * @param $uri The URI to split by a ?.
 * @retval string Returns the first part of the URI.
 */
function asfw_controller_create_base_uri($uri) {
	$uri = explode('?', $uri);
	return current($uri);
}

/**
 * Removes the first / and last / off of a string. For example:
 * /index.php/controller/method/value1/ becomes
 * index.php/controller/method/value1
 * @author vmc <vmc@leftnode.com>
 * @param $string The string to sanitize.
 * @retval string Returns the sanitized string.
 */
function asfw_strip_end_slashes($string) {
	$last = strlen($string)-1;
	if ( $last <= 0 ) {
		return NULL;
	}

	// Strip off the last / if it exists
	if ( $string[$last] == '/' ) {
		$string = substr($string, 0, $last);
	}

	// Strip off the first / if it exists
	if ( $string[0] == '/' ) {
		$string = substr($string, 1);
	}
	
	return $string;
}



function asfw_class_name_to_file_name($class_name) {
	$class_name = strtolower(trim($class_name));
	
	
}