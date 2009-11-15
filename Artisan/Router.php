<?php

require_once 'Artisan/Functions/String.php';

require_once 'Artisan/Controller/Exception.php';

define('CONTROLLER_SUFFIX', '_Controller');
define('CONTROLLER_EXT', '.php');

define('MODEL_VALIDATOR_SUFFIX', '_Model_Validator');

define('VIEW_EXT', '.phtml');
define('LAYOUT_EXT', '.phtml');

class Artisan_Controller_Router {
	private $_class = NULL;
	private $_method = NULL;
	private $_controller = NULL;
	
	private $_argv = array();
	
	private $_routerConfig = NULL;
	
	const LOCAL_DIR = 'local';
	const VIEW_DIR = 'View';
	
	public function __construct(Artisan_Config $config) {
		$this->_routerConfig = $config;
	}

	public function __destruct() {
		unset($this->_class, $this->_method, $this->_controller, $this->_argv);
	}
	
	public function dispatch() {
		$this->_parseUri();
		
		$ds = DIRECTORY_SEPARATOR;
		
		$root = $this->_routerConfig->root_dir . $ds;
		$layout_dir = $root . $this->_routerConfig->layout_dir . $ds;
		
		$controller_file = $root . $this->_controller . $ds . $this->_controller . CONTROLLER_EXT;
		
		if ( false === is_file($controller_file) ) {
			throw new Artisan_Controller_Exception("The controller file, '{$controller_file}' could not be found.");
		}
		
		require_once $controller_file;
	
		$controller = new $this->_class($this->_routerConfig, $this->_method, $this->_argv);
		$rendered_controller = $controller->load();
		
		return $rendered_controller;
	}
	
	private function _parseUri() {
		$uri = asfw_exists_return('u', $_REQUEST);
		$uri = trim($uri, '/');
		
		$uri_bits = array();
		if ( false === empty($uri) ) {
			$uri_bits = explode('/', $uri);
		}
		
		/**It
		 * Bit 0 is the Controller
		 * Bit 1 is the Method
		 * Any bit after that are the parameters to pass to the method.
		 */
		$controller = @$uri_bits[0];
		if ( false === isset($uri_bits[0]) ) {
			$controller = $this->_routerConfig->default_controller;
		}
		
		$controller = strtolower(trim($controller));
	
		// Strip out non-ascii characters
		$controller = asfw_rename_controller($controller);
		
		$this->_controller = $controller;
		$this->_file = $controller . CONTROLLER_EXT;
		$this->_class = $controller . CONTROLLER_SUFFIX;
		
		$method = @$uri_bits[1];
		if ( false === isset($uri_bits[1]) ) {
			$method = $this->_routerConfig->default_method;
		}
		
		$method = asfw_rename_controller_method($method);
		$this->_method = $method;
		
		if ( count($uri_bits) > 2 ) {
			$this->_argv = array_slice($uri_bits, 2);
		}
	}
}
