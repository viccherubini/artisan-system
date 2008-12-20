<?php

/**
 * Writes a log array to the filesystem.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Log_Filesystem extends Artisan_Log_Writer {
	private $_save_dir = NULL;
	private $_log_name = NULL;
	
	public function __construct($location, $log_name) {
		$location = trim($location);
		if ( true === is_dir($location) ) {
			$this->_save_dir = $location;
		}
		
		$this->_log_name = trim($log_name);
	}
	
	public function flush(&$log) {
		if ( 0 === count($log) ) {
			return true;
		}

		foreach ( $log as $l ) {
			// Write log data to the file
		}
		return true;
	}
}