<?php

require_once 'Library.php';

class Artisan_Router {
	private $class = NULL;
	private $method = NULL;
	private $controller = NULL;
	private $argv = array();
	private $config = NULL;
	private $ext = '.php';
	private $suffix = '_Controller';
	
	public function __construct($config) {
		$this->setRouterConfig($config);
	}

	public function __destruct() {
		unset($this->class, $this->method, $this->controller, $this->config, $this->argv);
	}
	
	public function setConfig($config) {
		$this->config = $config;
	}
	
	public function dispatch() {
		$ds = DIRECTORY_SEPARATOR;
		
		$uri = er('_u', $_REQUEST);
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
			$controller = $this->config['default_controller'];
		}
		
		$controller = strtolower(trim($controller));
	
		// Strip out non-ascii characters
		$controller = rename_controller($controller);
		
		$this->controller = $controller;
		$this->file = $controller . $this->ext;
		$this->class = $controller . $this->suffix;
		
		$method = @$uri_bits[1];
		if ( false === isset($uri_bits[1]) ) {
			$method = $this->config['default_method'];
		}
		
		$method = rename_method($method);
		$this->method = $method;
		
		if ( count($uri_bits) > 2 ) {
			$this->argv = array_slice($uri_bits, 2);
		}
		
		$root = $this->config['root_dir'] . $ds;
		$layout_dir = $root . $this->config['layout_dir'] . $ds;
		
		$controller_file = $root . $this->controller . $ds . $this->controller . $this->ext;
		
		if ( false === is_file($controller_file) ) {
			throw new Artisan_Exception("The controller file, '{$controller_file}' could not be found.");
		}
		
		require_once $controller_file;
	
		$controller = new $this->class($this->config, $this->method, $this->argv);
		$rendered_controller = $controller->load();
		
		return $rendered_controller;
	}
}
