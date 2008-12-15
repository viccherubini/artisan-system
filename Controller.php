<?php

/**
 * @see Artisan_Controller_Exception
 */
require_once 'Artisan/Controller/Exception.php';

/**
 * @see Artisan_Controller_Site
 */
require_once 'Artisan/Controller/Site.php';

require_once 'Artisan/Functions/String.php';

require_once 'Artisan/Functions/Array.php';

require_once 'Artisan/Controller/View.php';

/**
 * Handles the Model-View Controller design pattern. The Plugin architecture allows
 * one to easily push a class (Aritsan or not) into the controller so that children
 * classes can easily take advantage of them.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Controller {
	///< Because this class is a singleton, the instance of this class.
	private static $INST = NULL;

	///< Instance of the controller specified to use.
	private $CONTROLLER = NULL;
	
	///< The directory to load controllers from.
	private $_directory = '';
	
	///< If no controller is specified, this one is used.
	private $_default_controller = '';
	
	///< If no method is specified, this one is used.
	private $_default_method = 'index';
	
	///< The name of the controller currently being used.
	private $_controller_name = NULL;
	
	///< The method being executed in the controller.
	private $_controller_method = NULL;

	///< The list of arguments to pass into the controller method
	private $_controller_argv = array();

	///< Whether or not the configuration has been set.
	private $_config_set = true;
	
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
	 * Destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	public function __destruct() {
		unset($this->CONTROLLER);
	}
	
	/**
	 * Returns this class for usage as a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns the itself.
	 */
	public static function &get() {
		if ( true === is_null(self::$INST) ) {
			self::$INST = new self;
		}
		return self::$INST;
	}
	
	/**
	 * Sets the configuration if not set through the constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C Configuration object.
	 * @retval boolean Returns true.
	 */
	public function setConfig(Artisan_Config &$C) {
		$this->setDirectory($C->directory);
		$this->_default_method = $C->default_method;
		$this->_default_controller = $C->default_controller;
		$this->_config_set = true;
		return true;
	}
	
	/**
	 * Sets the directory that contains the controller classes.
	 * @author vmc <vmc@leftnode.com>
	 * @param $directory The name of the directory, must be valid.
	 * @retval boolean Returns true.
	 */
	public function setDirectory($directory) {
		$directory = trim($directory);
		if ( true === is_dir($directory) ) {
			$this->_directory = $directory;
		}
		return true;
	}
	
	/**
	 * Executes the loaded controller and method.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Controller_Exception If the configuration has not been set.
	 * @throw Artisan_Controller_Exception If parsing the URI does not execute correctly.
	 * @throw Artisan_Controller_Exception If the controller file does not exist.
	 * @throw Artisan_Controller_Exception If the controller class does not exist in the controller file.
	 * @throw Artisan_Controller_Exception If the controller instance is not inherited from Artisan_Controller.
	 * @throw Artisan_Controller_Exception If the method specified does not exist in the Controller.
	 * @throw Artisan_Exception Any exception thrown from the specified Controller.
	 * @retval boolean Returns true.
	 */
	public function execute() {
		if ( false === $this->_config_set ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'The ' . __CLASS__ . ' Configuration was not set.', __CLASS__, __METHOD__);
		}
		
		try {
			$this->_parseUri();
		} catch ( Artisan_Controller_Exception $e ) {
			throw $e;
		}
		
		$controller = trim($this->_controller_name);
		
		
		// So we have the controller now
		// and the method to use
		// and the arguments to send to the controller.
		// So lets do all of that, and then call
		// the view->execute() to set the layout_content
		// and display that.
		

		// See if that file exists in the directory
		$controller_file = $this->_directory . DIRECTORY_SEPARATOR . $controller . '.php';
		if ( false === is_file($controller_file) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'Controller file ' . $controller_file . ' was not found.', __CLASS__, __FUNCTION__);
		}

		// File exists, load it up
		@require_once $controller_file;

		
		// Ensure the class exists
		if ( false === class_exists($controller) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'Class ' . $this->_controller_name . ' not found in file ' . $controller_file . '.', __CLASS__, __FUNCTION__);
		}
		
		// Create a new instance of the controller to work with
		try {
			$this->CONTROLLER = new $controller();
		} catch ( Artisan_Exception $e ) {
			throw $e;
		}

		
		if ( false === $this->CONTROLLER instanceof Artisan_Controller_View ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'The controller is not of inherited type ' . __CLASS__, __CLASS__, __FUNCTION__);
		}
		
		$method = $this->_controller_method;
		if ( false === method_exists($this->CONTROLLER, $method) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'The method ' . $method . ' does not exist in the controller ' . $controller . '.', __CLASS__, __FUNCTION__);
		}
		
		$M = new ReflectionMethod($this->CONTROLLER, $method);

		$param_count = $M->getNumberOfRequiredParameters();
		$argv = $this->_controller_argv;
		$argc = count($argv);
		if ( $param_count != $argc ) {
			if ( $param_count > $argc ) {
				$argv = array_pad($argv, $param_count, NULL);
			}
		}

		try {
			if ( true === $M->isPublic() ) {
				if ( true === $M->isStatic() ) {
					$M->invokeArgs(NULL, $argv);
				} else {
					$M->invokeArgs($this->CONTROLLER, $argv);
				}
			}
		} catch ( Artisan_Exception $e ) {
			throw $e;
		}
		
		$this->CONTROLLER->__setControllerDirectory($this->_directory);
		$content = $this->CONTROLLER->__execute($controller, $method);
		
		return $content;
	}
	
	/**
	 * Parses the URI to extract the appropriate parameters. Sets the appropriate internal
	 * methods with the specified Controller, Method, and arguments.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Controller_Exception If the SCRIPT_NAME or REQUEST_URI is not specified in the $_SERVER superglobal.
	 * @retval boolean Returns true.
	 */
	private function _parseUri() {
		$script_name = asfw_exists_return('SCRIPT_NAME', $_SERVER);
		$request_uri = asfw_exists_return('REQUEST_URI', $_SERVER);

		if ( true === empty($script_name) || true === empty($request_uri) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'The SCRIPT_NAME or REQUEST_URI is empty.', __CLASS__, __METHOD__);
		}

		$script_name = asfw_strip_end_slashes($script_name);
		$request_uri = asfw_strip_end_slashes(asfw_controller_create_base_uri($request_uri));

		$request_uri = urldecode($request_uri);
		$request_bits = explode('/', $request_uri);

		// See if the first element of the array is also the script name, if so, remove it
		if ( trim(current($request_bits)) == trim($script_name) ) {
			$request_bits = array_slice($request_bits, 1);
		}

		/**
		 * NORMALIZE THE BITS
		 * Bit 0 is the Controller
		 * Bit 1 is the Method
		 * Any bit after that are the parameters to pass to the method.
		 */
		if ( false === asfw_exists(0, $request_bits) ) {
			$request_bits[0] = $this->_default_controller;
		}

		if ( false === asfw_exists(1, $request_bits) ) {
			$request_bits[1] = $this->_default_method;
		}

		$this->_controller_name = asfw_rename_controller($request_bits[0]);
		$this->_controller_method = asfw_rename_controller_method($request_bits[1]);
		
		if ( count($request_bits) > 2 ) {
			$this->_controller_argv = array_slice($request_bits, 2);
		}

		return true;
	}
}