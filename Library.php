<?php

function pre_print_r($array, $return = false) {
	$str = '<pre>' . print_r($array, true) . '</pre>';
	if ( true === $return ) {
		return $str;
	} else {
		echo $str;
	}
	
	return true;
}

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

function create_field_list($table, $fields, $table_alias = NULL) {
	$field_list = array();
	
	if ( true === is_array($fields) ) {
		$field_list = array_map('create_field_alias', $fields, array_fill(0, count($fields), $table_alias));
	}
	
	return $field_list;
}

function create_field_alias($field, $table_alias) {
	return ( false === empty($table_alias) ? $table_alias . '.' : NULL ) . str_replace('`', NULL, $field);
}

function sanitize_field_list($field_list) {
	foreach ( $field_list as $i => $value ) {
		$field_list[$i] = str_replace("`", NULL, $value);
	}
	return $field_list;
}

function create_table_alias($table) {
	$alias = NULL;
	$table = trim($table);
	$table = str_replace('_', ' ', $table);
	
	$words = explode(' ', $table);
	foreach ( $words as $word ) {
		$word = trim($word);
		if ( false === empty($word) ) {
			$alias .= $word[0];
		}
	}
	
	return $alias;
}

function now($time = NULL) {
	if ( true === empty($time) ) {
		$time = time();
	}
	return date('Y-m-d H:i:s', $time);
}

function is_assoc($array) {
	if ( false === is_array($array) ) {
		return false;
	}
	
	$is_assoc = true;
	$keys = array_keys($array);
	foreach ( $keys as $key ) {
		if ( false === is_string($key) ) {
			$is_assoc = false;
		}
	}
	
	return $is_assoc;
}