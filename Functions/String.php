<?php

/**
 * Returns the first letter of a string.
 */
function asfw_first_letter($word) {
	$w = NULL;
	$word = trim($word);
	if ( false === empty($word) ) {
		$w = $word[0];
	}
	return $w;
}

function asfw_rename_controller($controller) {
	$controller = strtolower($controller);
	
	// Strip out non-ascii characters
	$controller = preg_replace('/[^a-z_0-9]/i', NULL, $controller);
	$controller = str_replace('_', ' ', $controller);
	$controller = ucwords($controller);
	$controller = str_replace(' ', '_', $controller);
	
	return $controller;
}
