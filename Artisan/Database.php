<?php


Artisan_Library::load('Database/Monitor');
Artisan_Library::load('Database/Exception');

abstract class Artisan_Database {
	protected $CONFIG = NULL;
	
	public function __construct(Artisan_Config &$C) {
		/*
		if ( false === is_null($config) ) {
			self::$config = $config;
			
			$type = @$config->type;
			
			if ( false === empty($type) ) {
				$type = ucwords($type);
				$class = 'Artisan_Database_' . $type;
				
				Artisan_Library::load('Database/' . $type);
				
				$db = new $class();
				
				return $db;
			}
		}
		*/
	}

	public function setConfig(Artisan_Config &$C) {
		$this->CONFIG = $C;
	}
	
	public function &getConfig() {
		return $this->CONFIG;
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
}

?>