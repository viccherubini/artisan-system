<?php

require_once 'Func.Library.php';

class Artisan_Session {
	private static $instance = NULL;
	private $save_handler = NULL;
	private $session_name;
	private $session_id;
	private $started = false;

	private function __construct() { }
	
	private function __clone() { }
	
	public function __destruct() { }
	
	public static function get() {
		if ( true === is_null(self::$instance) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	public function start($session_name = NULL) {
		if ( false === empty($session_name) ) {
			$match_alpha_num = preg_match('/^[a-zA-Z0-9]+$/', $session_name);
			if ( false === $match_alpha_num || true === is_numeric($session_name) ) {
				$session_name = NULL;
			}
		}

		if ( false === empty($session_name) ) {
			$this->session_name = $session_name;
			session_name($session_name);
		} else {
			$this->session_name = session_name();
		}
		
		$started = session_start();
		$this->session_id = session_id();

		$this->started = true;
		
		return $started;
	}
	
	public function destroy() {
		// Really delete the session! Actually, this removes the entire session
		// and ensures all data is deleted. The second value of setcookie() is
		// intentionally not NULL because IE messes up if you send a NULL cookie.
		// Also, this is set at the beginning of time! :)		
		if ( true === isset($_COOKIE[$this->session_name]) ) {
			setcookie($this->session_name, $this->session_id, 1, '/');
		}

		session_destroy();
		
		return true;
	}
	
	public function add($name, $value) {
		if ( true === $this->started ) {
			$_SESSION[$name] = $value;
		}
		return $this;
	}
	
	public function remove($name) {
		if ( true === $this->exists($name) ) {
			unset($_SESSION[$name]);
		}
		return true;
	}
	
	public function exists($name) {
		if ( true === $this->started && true === isset($_SESSION[$name]) ) {
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
		return $this->started;
	}
}