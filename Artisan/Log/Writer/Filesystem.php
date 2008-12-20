<?php

/**
 * @see Artisan_Log_Writer
 */
require_once 'Artisan/Log/Writer.php';

/**
 * Writes a log array to the filesystem.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Log_Writer_Filesystem extends Artisan_Log_Writer {
	///< The name of the directory to save the file in.
	private $_save_dir = NULL;
	
	///< The name of the log file.
	private $_log_name = NULL;
	
	/**
	 * Default constructor to build a filesystem writer.
	 * @author vmc <vmc@leftnode.com>
	 * @param $location The location directory to write the log in, must be a valid directory.
	 * @param $log_name The name of the log file to write.
	 * @retval Object Returns new Artisan_Log_Filesystem.
	 */
	public function __construct($location, $log_name) {
		$this->setLocation($location);
		$this->setName($log_name);
	}
	
	/**
	 * Sets the location to write the log to.
	 * @author vmc <vmc@leftnode.com>
	 * @param $location The name of the location to write to. Must be a valid path.
	 * @retval boolean Returns true.
	 */
	public function setLocation($location) {
		$location = trim($location);
		if ( true === is_dir($location) ) {
			$this->_save_dir = $location;
		}
		return true;
	}
	
	/**
	 * Sets the name of the file to write the log data to.
	 * @author vmc <vmc@leftnode.com>
	 * @param $log_name The name of the log file.
	 * @retval boolean Returns true.
	 */
	public function setName($log_name) {
		$this->_log_name = trim($log_name);
		return true;
	}
	
	/**
	 * Write the log data out to the specified directory.
	 * @author vmc <vmc@leftnode.com>
	 * @param $log Reference to the log array to print.
	 * @retval boolean Returns true.
	 */
	public function flush(&$log) {
		if ( 0 === count($log) ) {
			return false;
		}
		
		// Ensure the directory is writable to supress warnings.
		if ( false === is_writable($this->_save_dir) ) {
			return false;
		}
		
		$log_file = $this->_save_dir . DIRECTORY_SEPARATOR . $this->_log_name;
		
		// Although its generally a bad idea to suppress warnings, doing it here is
		// necessary to avoid having to log the warnings!
		$fh = @fopen($log_file, 'a');
		
		foreach ( $log as $l ) {
			foreach ( $l as $k => $v ) {
				fwrite($fh, '[' . $k . '] => ' . trim($v) . "\n");
			}
			fwrite($fh, str_repeat('=', 80) . "\n");
		}
		fclose($fh);
		return true;
	}
}