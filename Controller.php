<?php


Artisan_Library::load('Controller/Exception');
Artisan_Library::load('Controller/Plugin');
Artisan_Library::load('Controller/Builder');

/**
 * Handles the Model-View Controller design pattern. The Plugin architecture allows
 * one to easily push a class (Aritsan or not) into the controller so that children
 * classes can easily take advantage of them.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Controller {
	///< Instance of the Artisan_Controller_Plugin class.
	protected $P = NULL;
	
	///< Instance of the controller specified to use.
	private $CONTROLLER = NULL;
	
	///< The directory to load controllers from.
	private $_directory = NULL;
	
	///< If no method is specified, this one is used.
	private $_default_method = 'index';
	
	///< If no controller is specified, this one is used.
	private $_default_controller = NULL;
	
	///< The name of the controller currently being done.
	private $_controller_name = NULL;

	/**
	 * Builds a new Artisan_Controller instance.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C Optional configuration parameter of type Artisan_Config.
	 * @retval Object Returns new Artisan_Controller instance.
	 */
	public function __construct(Artisan_Config &$C = NULL) {
		$this->P = &Artisan_Controller_Plugin::get();
		
		if ( true === is_object($C) ) {
			$this->setConfig($C);
		}
	}

	public function __destruct() {
	
	}
	
	public function setConfig(Artisan_Config &$C) {
		$this->setDirectory($C->directory);
		$this->_default_method = $C->default_method;
		$this->_default_controller = $C->default_controller;
	}
	
	
	public function setDirectory($directory) {
		$directory = trim($directory);
		if ( true === is_dir($directory) ) {
			$this->_directory = $directory;
		}
	}
	
	
	public function load($controller = NULL) {
		if ( true === empty($this->_directory) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'No controller directory specified.', __CLASS__, __FUNCTION__);
		}
		
		if ( false === is_dir($this->_directory) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'Value specified for controller directory, ' . $this->_directory . ', is not a directory.', __CLASS__, __FUNCTION__);
		}
		
		// See if the controller isn't set and the default controller is.
		// If so, use that, otherwise, throw an exception.
		if ( true === empty($controller) && true === empty($this->_default_controller) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'No controller and default controller specified.', __CLAS__, __FUNCTION__);
		}
		
		if ( true === empty($controller) ) {
			$controller = $this->_default_controller;
		}
		
		// Correctly name the controller according to the naming conventions
		$controller = asfw_rename_controller($controller);
		$this->_controller_name = $controller;
		
		// See if that file exists in the directory
		$controller_file = $this->_directory . $controller . EXT;
		if ( false === is_file($controller_file) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'Controller file ' . $controller_file . ' was not found.', __CLASS__, __FUNCTION__);
		}
		
		// File exists, load it up
		require_once $controller_file;
		
		// Ensure the class exists
		if ( false === class_exists($controller) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'Class ' . $controller . ' not found in file ' . $controller_file . '.', __CLASS__, __FUNCTION__);
		}
		
		// Create a new instance of the controller to work with
		try {
			$this->CONTROLLER = new $controller();
		} catch ( Artisan_Exception $e ) {
			throw $e;
		}
	}
	
	public function execute($method = NULL, $args = array()) {
		if ( false === is_object($this->CONTROLLER) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'The controller has not been set yet.', __CLASS__, __FUNCTION__);
		}
		
		if ( false === $this->CONTROLLER instanceof Artisan_Controller ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'The controller is not of inherited type ' . __CLASS__, __CLASS__, __FUNCTION__);
		}
		
		// Make sure whatever is being executed can be called with is_callable or method_exists
		if ( false === empty($method) && empty($this->_default_method) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'No controller method was specified and no default method is specified.', __CLASS__, __FUNCTION__);
		}
		
		if ( true === empty($method) ) {
			$method = $this->_default_method;
		}
		
		if ( false === method_exists($this->CONTROLLER, $method) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'The method ' . $method . ' does not exist in the controller ' . $this->_controller_name . '.', __CLASS__, __FUNCTION__);
		}
		
		// See if a translation exists for this method and if so,
		// get the data from the $data variable.
		$method = new ReflectionMethod($this->CONTROLLER, $method);

		try {
			if ( true === $method->isPublic() ) {
				if ( true === $method->isStatic() ) {
					$method->invokeArgs(NULL, $args);
				} else {
					$method->invokeArgs($this->CONTROLLER, $args);
				}
			}
		} catch ( Artisan_Exception $e ) {
			throw $e;
		}
	}
}

?>
