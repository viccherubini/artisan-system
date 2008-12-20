<?php

/**
 * This file holds methods for easily database manipulation and construction.
 * @author vmc <vmc@leftnode.com>
 */


/**
 * Turn a database field from `field_name` to `table_alias`.`field_name`.
 * @author vmc <vmc@leftnode.com>
 * @param $field The field to create a proper name for.
 * @param $table_alias The alias of the table the field is a member of.
 * @retval Returns the aliasized database field name.
 */
function asfw_create_field_alias($field, $table_alias) {
	return ( false === empty($table_alias) ? $table_alias . '.' : NULL ) . str_replace('`', NULL, $field);
}

/**
 * @author vmc <vmc@leftnode.com>
 * @param $table The table to map to each field.
 * @param $fields An array of fields to map the table alias to.
 * @param $table_alias Optional parameter to manually define a table alias.
 */
function asfw_create_field_list($table, $fields, $table_alias = NULL) {
	$field_list = array();
	
	if ( true === is_array($fields) ) {
		$field_list = array_map("asfw_create_field_alias", $fields, array_fill(0, count($fields), $table_alias));
	}
	
	return $field_list;
}

/**
 * @author vmc <vmc@leftnode.com>
 * @param $field_list An array of fields to strip the backticks (`) out of
 * @retval array An array of sanitized fields
 */
function asfw_sanitize_field_list($field_list) {
	foreach ( $field_list as $i => $value ) {
		$field_list[$i] = str_replace("`", NULL, $value);
	}
	return $field_list;
}


/**
 * Returns an alias for a database table, for example, `my_customer_list` would
 * return as `mcl`. Takes first letter of each word separated by a space or underscore.
 * @author vmc <vmc@leftnode.com>
 * @param $table The name of the table to create an alias for.
 * @retval string The table alias name.
 */
function asfw_create_table_alias($table) {
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

/**
 * Returns the equivalent of the database NOW() method for date/datetime
 * field types.
 * @author vmc <vmc@leftnode.com>
 * @retval string The datetime value in format YYYY-MM-DD HH:MM:SS
 */
function asfw_now($time = NULL) {
	if ( true === empty($time) ) {
		$time = time();
	}
	
	return date('Y-m-d H:i:s', $time);
}
