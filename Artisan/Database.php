<?php


Artisan_Library::load('Database/Monitor');

abstract class Artisan_Database {
	protected static $config = array();
	
	public function __construct($config = array()) {
		if ( true === is_array($config) && count($config) > 0 ) {
			self::$config = $config;
		}
	}

	abstract public function connect();

	abstract public function disconnect();
	
	abstract public function rowCount();
	abstract public function rowsAffected();
	
	abstract public function query(Artisan_Sql $sql);
	
	abstract public function fetch();
	abstract public function free();
	
	abstract public function isConnected();
	
	abstract public function escape($string);
	
	abstract protected function _start();
	abstract protected function _cancel();
	abstract protected function _end();
	
	abstract public function queue($query_list);
}

?>
