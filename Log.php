<?php

Artisan_Library::load('Log/Exception');

define('LOG_GENERAL', 'G', false);
define('LOG_ERROR', 'E', false);
define('LOG_SUCCESS', 'S', false);
define('LOG_EXCEPTION', 'X', false);

/**
 * Abstract method for logging data to a certain place.
 * @author vmc <vmc@leftnode.com>
 * @todo Finish writing this class!
 */
abstract class Artisan_Log {
	///< The array of log data to flush out.
	protected $_log = array();
	
	///< The list of data to flush out, G = General, E = Error, S = Success, X = Exception
	protected $_flush_level_list = array('G', 'E', 'S', 'X');

	/**
	 * Default constructor to write data to a specified source.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C Configuration object.
	 * @retval Object Returns a new Artisan_Log object.
	 */
	public function __construct(Artisan_Config &$C = NULL) {
		if ( false === empty($C) ) {
			if ( true === asfw_exists('flush_level_list', $C) ) {
				$this->_flush_level_list = explode(',', str_replace(' ', NULL, $C->flush_level_list));
			}
		}
	}
	
	/**
	 * Destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	public function __destruct() { }
	
	/**
	 * Adds an item onto the log class.
	 * @author vmc <vmc@leftnode.com>
	 * @param $log_type The type of log to create, as defined by the constants above.
	 * @param $log_text The text of the log.
	 * @param $log_class The class to log against.
	 * @param $log_function The method to log against.
	 * @param $log_trace If the log entry is an exception, this will contain the trace.
	 * @retval boolean Return true.
	 */
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
	
	/**
	 * Adds an exception to the log class.
	 * @author vmc <vmc@leftnode.com>
	 * @param $E The exception to push onto the log class.
	 * @retval boolean Return true.
	 */
	public function addEx(Artisan_Exception &$E) {
		$this->add(
			LOG_EXCEPTION,
			$E->toString(),
			$E->getClassName(),
			$E->getFunctionName(),
			$E->getTraceAsString()
		);
		
		return true;
	}
	
	/**
	 * Sets the different flush levels. See $_flush_level_list for default values.
	 * @author vmc <vmc@leftnode.com>
	 * @param $flush_level_list An array of flush levels.
	 * @retval boolean Returns true.
	 */
	public function setFlushLevels($flush_level_list) {
		if ( false === is_array($flush_level_list) ) {
			return false;
		}
		
		$this->_flush_level_list = $flush_level_list;
		
		return true;
	}

	/**
	 * Abstract method so different logs can flush to different areas.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	abstract public function flush();
}
