<?php

function exs($k, $a) {
	if ( true === is_object($a) && true === isset($a->$k) ) {
		return true;
	}
	
	if ( true === is_array($a) && true === isset($a[$k]) ) {
		return true;
	}
	
	return false;
}

function er($k, $a, $return = NULL) {
	if ( true === is_object($a) && true === isset($a->$k) ) {
		return $a->$k;
	}
	
	if ( true === is_array($a) && true === isset($a[$k]) ) {
		return $a[$k];
	}
	
	return $return;
}


function rename_controller($controller) {
	$controller = strtolower(trim($controller));
	
	$controller = preg_replace('/[^a-z_0-9]/i', NULL, $controller);
	$controller = str_replace('_', ' ', $controller);
	$controller = ucwords($controller);
	$controller = str_replace(' ', '_', $controller);
	
	return $controller;
}

function rename_method($method) {
	$method = preg_replace('/[^a-z_0-9]/i', NULL, $method);
	return strtolower($method);
}

function rename_view($view) {
	$view = preg_replace('/[^a-z_0-9\-]/i', NULL, $view);
	return strtolower($view);
}