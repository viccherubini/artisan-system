<?php

Artisan_Library::load('Session/Exception');
Artisan_Library::load('Session/Interface');

/**
 * Singleton class for handling sessions. It allows different session handlers,
 * such as a database and filesystem.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Session {
	///< Since this is a singleton, the instance of this class.
	private static $INST = NULL;

	///< The save_handler instance for how this class should be built.
	private $SH;

	///< The name of the session set.
	private $_session_name;
	
	///< The ID of the session set.
	private $_session_id;

	///< Whether or not the session has started.
	private $_started = false;

	/**
	 * Private constructor because this class is a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	private function __construct() { }
	
	/**
	 * Private clone method because this class is a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	private function __clone() { }
	
	/**
	 * Public destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function __destruct() {
		
	}
	
	/**
	 * The method to return the instance of this class.
	 * This method provides a singleton interface to this class.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns an instance of the Artisan_Session class.
	 */
	public static function &get() {
		if ( true === is_null(self::$INST) ) {
			self::$INST = new self;
		}
	
		return self::$INST;
	}
	
	/**
	 * Sets the configuration to this class so the save_handler can be loaded.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C The configuration object to collect data from, passed by reference.
	 * @throw Artisan_Session_Exception If the key save_handler is not present in the configuration.
	 * @throw Artisan_Session_Exception If the save_handler fails to set.
	 * @retval boolean Returns true.
	 */
	public function setConfig(Artisan_Config &$C) {
		if ( false === asfw_exists('save_handler', $C) ) {
			throw new Artisan_Session_Exception(ARTISAN_WARNING, 'save_handler is not present in the configuration.', __CLASS__, __FUNCTION__);
		}
		
		try {
			$this->setSaveHandler($C->save_handler);
		} catch ( Artisan_Session_Exception $e ) {
			throw $e;
		}
		
		return true;
	}
	
	/**
	 * Sets the save_handler object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $S The save_handler object to save session data to.
	 * @throw Artisan_Session_Exception If the parameter is not of type Artisan_Session.
	 * @retval boolean Returns true.
	 */
	public function setSaveHandler(&$S) {
		if ( false === is_object($S) ) {
			throw new Artisan_Session_Exception(ARTISAN_WARNING, 'The save_handler sent in $S is not an object.', __CLASS__, __FUNCTION__);
		}
	
		if ( false === in_array('Artisan_Session_Interface', class_implements($S)) ) {
			throw new Artisan_Session_Exception(ARTISAN_WARNING, 'The save_handler instance passed is an object, but does not implement Artisan_Session_Interface.', __CLASS__, __FUNCTION__);
		}
		
		$this->SH = &$S;
		
		return true;
	}
	
	/**
	 * Starts a session and uses a session handler.
	 * @author vmc <vmc@leftnode.com>
	 * @param $session_name Optional parameter to name the session. Must match regex /^[a-zA-Z0-9]+$/
	 * @throw Artisan_Session_Exception If the session handler variable ($this->SH) is not an object. Must be configured first.
	 * @retval boolean Returns true.
	 */
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
		}

		// Set up all of the save handlers
		if ( false === is_object($this->SH) ) {
			throw new Artisan_Session_Exception(ARTISAN_WARNING, 'The save_handler is not yet configured, please run setSaveHandler() first.', __CLASS__, __FUNCTION__);
		}

		// Let PHP know we're using our own save handlers
		ini_set('session.save_handler', 'user');

		/**
		 * IMPORTANT! This must be here: the write() method of the save_handler
		 * is called at the end of the program execution, meaning the destructor
		 * for class instances are called. The database class instance's destructor
		 * will be called by the time write() is run, which the destructor disconnects
		 * from the database and kills the configuration data. By putting this here,
		 * the shutdown function session_write_close() will be called before that
		 * occurs, allowing the data to be written properly.
		*/
		register_shutdown_function("session_write_close");

		// $this->SH is guaranteed to have these methods because it has
		// to implement the interface Artisan_Session_Interface
		session_set_save_handler(
			array(&$this->SH, 'open'),
			array(&$this->SH, 'close'),
			array(&$this->SH, 'read'),
			array(&$this->SH, 'write'),
			array(&$this->SH, 'destroy'),
			array(&$this->SH, 'gc')
		);

		$started = session_start();
		$this->_session_id = session_id();

		$this->_started = true;
		return $started;
	}
	
	/**
	 * Destroys a session permanently. Calls the session handler to delete all of the
	 * data in there as well.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function destroy() {
		session_destroy();

		/**
		 * Really delete the session! Actually, this removes the entire session
		 * and ensures all data is deleted. The second value of setcookie() is
		 * intentionally not NULL because IE messes up if you send a NULL cookie.
		 * Also, this is set at the beginning of time! :)
		 */
		if ( true === asfw_exists($this->_session_name, $_COOKIE) ) {
			setcookie($this->_session_name, '', 1, '/');
		}
		
		return true;
	}	
	
	/**
	 * Pushes an element onto the session.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the value to add.
	 * @param $value The value of the variable to add.
	 * @retval boolean Returns true.
	 */
	public function add($name, $value) {
		if ( true === $this->_started ) {
			$_SESSION[$name] = $value;
		}
		
		return true;
	}
	
	/**
	 * Pops an element from the session
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the value to pop.
	 * @retval boolean Returns true.
	 */
	public function remove($name) {
		if ( true === $this->_started ) {
			if ( true === asfw_exists($name, $_SESSION) ) {
				unset($_SESSION[$name]);
			}
		}
		
		return true;
	}
	
	/**
	 * Determines if an element exists in the session.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the element to check.
	 * @retval boolean Returns true if the element exists, false otherwise.
	 */
	public function exists($name) {
		if ( true === $this->_started ) {
			if ( true === asfw_exists($name, $_SESSION) ) {
				return true;
			}
		}
		
		return false;
	}
}
