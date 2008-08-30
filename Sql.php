<?php

Artisan_Library::load('Sql/Monitor');
Artisan_Library::load('Sql/Exception');

class Artisan_Sql {
	
	const SQL_AND = 'AND';
	const SQL_OR = 'OR';
	

	public function __construct() {
		
	}
	

	public function __destruct() {

	}

	
	
	
	
	
	/*
	protected static function createFieldList($table, $fields, $alias = NULL) {
		$field_list = array();
		
		if ( true === is_array($fields) ) {
			$field_list = array_map("aliasize", $fields, array_fill(0, count($fields), $alias));
		}
		
		return $field_list;
	}
	
	protected static function _where($table, $fields, $field_data, $type = 'AND', $alias = NULL) {
		$type = strtoupper($type);
		
		if ( 'OR' != $type && 'AND' != $type ) {
			$type = 'AND';
		}
		
		$type = str_pad($type, strlen($type) + 2, ' ', STR_PAD_BOTH);
		
		if ( false === is_array($fields) || count($fields) < 1 ) {
			$fields = array($fields);
		}
		
		$field_list = self::createFieldList($table, $fields, $alias);
	
		$i=0;
		$fl = NULL;
		
		$safe_data_function = '$this->safeData';
		if ( true === Artisan_Library::exists('Database') ) {
			$db = Artisan_Database_Monitor::get();
			
			if ( true === is_object($db) ) {
				$safe_data_function = '$db->safeData';
			}
		}
		
		foreach ( $field_list as $field ) {
			$data = "''";
			if ( true === array_key_exists($i, $field_data) ) {
				$data = $field_data[$i];
			}
			
			$field = str_replace('?', "'" . $data . "'", $field);
			$fl .= ( 0 === $i ? NULL : $type . ' ' ) . $field;
			$i++;
		}
		
		$sql = ' WHERE ' . $fl;
		
		return $sql;
	}
	
	protected static function _query($sql) {
		if ( true === Artisan_Library::exists('Database') ) {
			$db = Artisan_Database_Monitor::get();
			
			if ( true === is_object($db) ) {
				$result = $db->querydb($sql);
				return $result;
			}
		}
		
		return NULL;
	}
	*/
	
	/**
	 * If there is no database connection, use this function to
	 * make a value safe for the database!
	 */
	/*
	private function safeData($str) {
		return addslashes($str);
	}
	*/
}

?>
