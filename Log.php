<?php

Artisan_Library::load('Log/Monitor');
Artisan_Library::load('Log/Exception');

abstract class Artisan_Log {
	
	protected static $_log = array();
	protected static $_flush_levels = array();
	
	
	public function __construct() {
	
	}
	
	public function __destruct() {
	
	}
	
	
	public function add($log_type, $log_text, $log_class = NULL, $log_function = NULL) {
		$log_id = uniqid('log_', true);
		
		self::$_log[$log_type][$log_id] = array(
			'log_date' => time(),
			'log_text' => $log_text,
			'log_class' => $log_class,
			'log_function' => $log_function,
			'log_ip' => NULL
		);
		
		return true;
	}
	
	public function addEx(Artisan_Exception $e) {
		$this->add(
			LOG_EXCEPTION,
			$e->toString(),
			$e->getClassName(),
			$e->getFunctionName()
		);
		
		return true;
	}
	
	abstract public function flush();
	
	public function setFlushLevels($flush_levels) {
		if ( false === is_array($flush_levels) ) {
			return false;
		}
		
		if ( count($flush_levels) > 4 ) {
			return false;
		}
		
		
	}

}

?>