<?php

/**
 * Singleton class for handling sessions.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Session {
	private static $_instance = NULL;
	private $save_handler = NULL;
	private $_session_name;
	private $_session_id;
	private $_started = false;

	private function __construct() { }
	
	private function __clone() { }
	
	public function __destruct() { }
	
	public static function get() {
		if ( true === is_null(self::$_instance) ) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	public function start($session_name = NULL) {
		if ( false === empty($session_name) ) {
			$match_alpha_num = preg_match('/^[a-zA-Z0-9]+$/', $session_name);
			if ( false === $match_alpha_num || true === is_numeric($session_name) ) {
				$session_name = NULL;
			}
		}

		if ( false === empty($session_name) ) {
			$this->_session_name = $session_name;
			session_name($session_name);
		} else {
			$this->_session_name = session_name();
		}
		
		$started = session_start();
		$this->_session_id = session_id();

		$this->_started = true;
		
		return $started;
	}
	
	public function destroy() {
		// Really delete the session! Actually, this removes the entire session
		// and ensures all data is deleted. The second value of setcookie() is
		// intentionally not NULL because IE messes up if you send a NULL cookie.
		// Also, this is set at the beginning of time! :)		
		if ( true === isset($_COOKIE[$this->_session_name]) ) {
			setcookie($this->_session_name, $this->_session_id, 1, '/');
		}

		session_destroy();
		
		return true;
	}
	
	public function add($name, $value) {
		if ( true === $this->_started ) {
			$_SESSION[$name] = $value;
		}
		return $this;
	}
	
	public function remove($name) {
		if ( true === $this->_started ) {
			if ( true === asfw_exists($name, $_SESSION) ) {
				unset($_SESSION[$name]);
			}
		}
		return true;
	}
	
	public function exists($name) {
		if ( true === $this->_started && true === isset($_SESSION[$name]) ) {
			return true;
		}
		return false;
	}
	
	public function key($name) {
		if ( true === $this->exists($name) ) {
			return $_SESSION[$name];
		}
		return NULL;
	}
	
	public function isStarted() {
		return $this->_started;
	}
}