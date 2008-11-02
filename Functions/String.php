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
 * @retval string The new safe controller name.,
*/
function asfw_rename_controller($controller) {
	$controller = strtolower($controller);
	
	// Strip out non-ascii characters
	$controller = preg_replace('/[^a-z_0-9]/i', NULL, $controller);
	$controller = str_replace('_', ' ', $controller);
	$controller = ucwords($controller);
	$controller = str_replace(' ', '_', $controller);
	
	return $controller;
}
