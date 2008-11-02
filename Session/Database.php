<?php

/**
 * This class allows the storage of sessions in a database. It is database agnostic
 * and dependent on whatever built database object is passed into it.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Session_Database implements Artisan_Session_Interface {
	///< Database instance passed into the class. Assumes the database already has a connection.
	private $DB = NULL;

	///< The max_lifetime that the session runs before garbage collection executes.
	private $_max_lifetime = 0;
	
	/**
	 * Default constructor for saving session data in a database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $DB A built and connected to database object.
	 * @retval Object New Artisan_Session_Database object.
	 */
	public function __construct(Artisan_Database &$DB) {
		$this->DB = &$DB;
		$this->_max_lifetime = intval(get_cfg_var("session.gc_maxlifetime"));
	}
	
	/**
	 * Destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Destroys the object.
	 */
	public function __destruct() {

	}
	
	/**
	 * Alias for the open save handler.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function open() {
		return true;
	}
	
	/**
	 * Calls garbage cleanup and removes old session data.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function close() {
		$this->gc($this->_max_lifetime); 
		return true;
	}
	
	/**
	 * Loads up session data from the database based on it's ID.
	 * @author vmc <vmc@leftnode.com>
	 * @param $session_id The ID of the session from which to load data.
	 * @retval Mixed Returns the data from the session, can be of any datatype.
	 */
	public function read($session_id) {
		$error = false;
		$session_data = NULL;
		try {
			$session_data = $this->DB->select
				->from('artisan_session', asfw_create_table_alias('artisan_session'), 'session_data')
				->where('session_id = ?', $session_id)
				->query()
				->fetch();
			if ( true === empty($session_data) ) {
				$session_data = NULL;
			}
		} catch ( Artisan_Database_Exception $e ) {
			$error = true;
		} catch ( Artisan_Sql_Exception $e ) {
			$error = true;
		}
		
		return $session_data;
	}
	
	/**
	 * Saves session data in the database. The database must have support for REPLACE
	 * to save data. Replace will insert the data if the session_id doesn't exist, or
	 * update it if it does.
	 * @author vmc <vmc@leftnode.com>
	 * @param $session_id The ID of the session to write data.
	 * @param $session_data The data to write, PHP will serialize this data.
	 * @retval Boolean Returns true if data was written, false otherwise.
	 */
	public function write($session_id, $session_data) {
		$error = false;
				
		try {
			$ipv4 = asfw_get_ipv4();
			$user_agent = asfw_get_user_agent();
			$user_agent_hash = sha1($user_agent);
			$this->DB->replace
				->into('artisan_session')
				->values($session_id, time(), $ipv4, $user_agent, $user_agent_hash, $session_data)
				->query();
		} catch ( Artisan_Database_Exception $e ) {
			$error = true;
		} catch ( Artisan_Sql_Exception $e ) {
			$error = true;
		}
		
		return !$error;
	}
	
	/**
	 * Destroys session data from the database based on the session ID.
	 * @author vmc <vmc@leftnode.com>
	 * @param $session_id The ID of the session for which to delete data.
	 * @retval boolean Returns true if data deleted, false otherwise.
	 */
	public function destroy($session_id) {
		$error = false;
		try {
			$this->DB->delete
				->from('artisan_session')
				->where('session_id = ?', $session_id)
				->query();
		} catch ( Artisan_Database_Exception $e ) {
			$error = true;
		} catch ( Artisan_Sql_Exception $e ) {
			$error = true;
		}
		
		return !$error;
	}
	
	/**
	 * Garbage collector, deletes all old data from the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $life The number of seconds before decayed data is deleted.
	 * @retval boolean Returns true if data deleted, false otherwise.
	 */
	public function gc($life) {
		$error = false;
		try {
			$del_time = time() - $life;
			$this->DB->delete
				->from('artisan_session')
				->where('session_expiration_time < ?', $del_time)
				->query();
		} catch ( Artisan_Database_Exception $e ) {
			$error = true;
		} catch ( Artisan_Sql_Exception $e ) {
			$error = true;
		}

		return !$error;
	}
}
