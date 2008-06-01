<?php


Artisan_Library::load('Database/Monitor');
Artisan_Library::load('Database/Exception');

abstract class Artisan_Database {
	protected static $config = NULL;
	
	public function __construct(Artisan_Config $config) {
		if ( false === is_null($config) ) {
			self::$config = $config;
		}
	}

	public function setConfig(Artisan_Config $config) {
		self::$config = $config;
	}
	
	abstract public function connect();

	abstract public function disconnect();
	
	abstract public function rowCount();
	abstract public function rowsAffected();
	
	abstract public function query(Artisan_Sql $sql);
	abstract public function queryFetch(Artisan_Sql $sql);
	
	abstract public function fetch();
	abstract public function free();
	
	abstract public function isConnected();
	
	abstract public function escape($string);
	
	abstract protected function _start();
	abstract protected function _cancel();
	abstract protected function _commit();
	
	abstract public function queue($query_list);
	abstract public function safeData($str);
}

?>