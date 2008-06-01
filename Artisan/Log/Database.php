<?php


class Artisan_Log_Database extends Artisan_Log {
	
	private $_db = NULL;
	
	public function __construct(Artisan_Config $config = NULL) {
		if ( true === is_object($config) ) {
			/*
			$type = @$config->type;
			$type = ucwords($type);
			
			$class = 'Artisan_Database_' . $type;
			$this->_db = new $class($config);
			*/
			
			// Want to do something like $db = new Artisan_Database($config) and it
			// knows to use the Mysqli based on the type
			//$this->_db = new Artisan_Database($config);
		} else {
			$db = Artisan_Database_Monitor::get();
			if ( true === is_object($db) && $db instanceof Artisan_Database ) {
				$this->_db = $db;			
			}
		}
		
		
		// If $_db is still null, throw an exception
		if ( true === is_null($this->_db) ) {
			throw new Artisan_Log_Exception(
				ARTISAN_ERROR_CORE,
				'Failed to initiliaze Log::Database class because no suitable database connection could be found.',
				__CLASS__,
				__FUNCTION__
			);
		}
	
	}
	
	public function flush() {
		pprint_r(parent::$_log);
	
	}

}

?>