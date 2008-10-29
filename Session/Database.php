<?php

class Artisan_Session_Database implements Artisan_Session_Interface {
	///< Database instance passed into the class. Assumes the database already has a connection.
	private $DB = NULL;
	
	
	private $_max_lifetime = 1440;
	
	public function __construct(Artisan_Database &$DB) {
		$this->DB = &$DB;
		$this->_max_lifetime = intval(get_cfg_var("session.gc_maxlifetime"));
	}
	
	public function __destruct() {

	}
	
	public function open() {
		return true;
	}
	
	public function close() {
		$this->gc($this->_max_lifetime); 
		return true;
	}
	
	public function read($session_id) {
		//echo 'reading ' . $session_id . '<br />';
	}
	
	public function write($session_id, $session_data) {
		$error = false;
		
		// Because not all databases support REPLACE, this is
		// intentionally inefficient to support the most database types
		try {
			$count = $this->DB->select
				->from('artisan_session')
				->where(array('session_id' => $session_id))
				->query()
				->numRows();
		} catch ( Artisan_Database_Exception $e ) {
			// Do nothing, assume count is 0 and insert into the database
			$count = 0;
			$error = true;
		}
		
		
		if ( 1 === $count ) {
			// Update
		} else {
			try {
				$this->DB->insert
					->into('artisan_session', array('session_id', 'session_expiration_time', 'session_data'))
					->values($session_id, time(), $session_data)
					->query();
			} catch ( Artisan_Database_Exception $e ) {
				// Do nothing
				$error = true;
			}
		}
		
		return !$error;
	}
	
	public function destroy($session_id) {
		$error = false;
		try {
			$this->DB->delete
				->from('artisan_session')
				->where(array('session_id' => $session_id))
				->query();
		} catch ( Artisan_Database_Exception $e ) {
			$error = true;
		}
		
		return !$error;
	}
	
	public function gc($life) {
		$error = false;
		try {
			$del_time = time() - $life;
			$this->DB->delete
				->from('artisan_session')
				->where(array('session_expiration_time <' => $del_time))
				->query();
		} catch ( Artisan_Database_Exception $e ) {
			$error = true;
		}

		return !$error;
	}
}
