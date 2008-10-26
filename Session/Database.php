<?php

class Artisan_Session_Database implements Artisan_Session_Interface {
	///< Database instance passed into the class. Assumes the database already has a connection.
	private $DB = NULL;
	
	public function __construct(Artisan_Database &$DB) {
		$this->DB = &$DB;
	}
	
	public function __destruct() {

	}
	
	public function open() {
		return true;
	}
	
	public function close() {
		return true;
	}
	
	public function read($session_id) {
		//echo 'reading ' . $session_id . '<br />';
	}
	
	public function write($session_id, $session_data) {
		// Because not all databases support REPLACE, this is
		// intentionally inefficient to support the most database types
		$count = $this->DB->select->from('artisan_session')->where(array('session_id' => $session_id))->query()->numRows();
		if ( 1 === $count ) {
			// Update
		} else {
			$this->DB->insert
				->into('artisan_session', array('session_id', 'session_expiration_time', 'session_data'))
				->values($session_id, time(), $session_data)
				->query();
		}
		
		return true;
	}
	
	public function destroy($session_id) {
		//echo 'destroying ' . $session_id . '<br />';
	}
	
	public function gc($life) {
		//echo 'garbage collecting older than ' . (time()-$life) . ' sessions.';
	}
}
