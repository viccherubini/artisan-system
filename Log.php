<?php

Artisan_Library::load('Log/Exception');

define('LOG_GENERAL', 'G', false);
define('LOG_ERROR', 'E', false);
define('LOG_SUCCESS', 'S', false);
define('LOG_EXCEPTION', 'X', false);


abstract class Artisan_Log {
	
	protected $_log = array();
	protected $_flush_level_list = array('G', 'E', 'S', 'X');

	public function __construct(Artisan_Config &$C = NULL) {
		if ( false === empty($C) ) {
			if ( true === asfw_exists('flush_level_list', $C) ) {
				$this->_flush_level_list = explode(',', str_replace(' ', NULL, $C->flush_level_list));
			}
		}
	}
	
	public function __destruct() {
	
	}
	
	
	public function add($log_type, $log_text, $log_class = NULL, $log_function = NULL, $log_trace = NULL) {
		$ip_address = asfw_get_ipv4();

		$this->_log[] = array(
			'log_date' => asfw_now(),
			'log_text' => $log_text,
			'log_trace' => $log_trace,
			'log_class' => $log_class,
			'log_function' => $log_function,
			'log_ip' => $ip_address,
			'log_type' => $log_type
		);
		
		return true;
	}
	
	public function addEx(Artisan_Exception $e) {
		$this->add(
			LOG_EXCEPTION,
			$e->toString(),
			$e->getClassName(),
			$e->getFunctionName(),
			$e->getTraceAsString()
		);
		
		return true;
	}
	
	abstract public function flush();
	
	public function setFlushLevels($flush_level_list) {
		if ( false === is_array($flush_level_list) ) {
			return false;
		}
		
		$this->_flush_level_list = $flush_level_list;
		
		return true;
	}

}
