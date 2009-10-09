<?php

/**
 * @see Artisan_Controller_Exception
 */
require_once 'Artisan/Controller/Exception.php';

require_once 'Artisan/Function/String.php';

require_once 'Artisan/Function/Array.php';

require_once 'Artisan/Controller/Router.php';

require_once 'Artisan/Controller/View.php';

/**
 * Main abstract Artisan_Controller class for managing an application controller.
 * All application controllers must extend this class to get the appropriate
 * render() method for rendering views. It is recommended that a root controller
 * class is created that extends this, and then each subsequent controller extends
 * that to avoid repeating common methods.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Controller {
	///< Artisan_Controller_View object for rendering data to the view.
	public $view = NULL;
	
	///< A list of block's to render to the layout.
	protected $_block_list = array();

	///< The location of the different layout files.
	protected $_layout_dir = NULL;
	
	///< The method from the URI of the controller to call.
	protected $_method = NULL;
	
	/**
	 * The name of the layout file (minus the extension) to load.
	 * This can be overwritten to NULL in a specific controller or
	 * controller method to just return the rendered view.
	 */
	protected $_layout = NULL;
	
	///< The string of the rendered view.
	protected $_rendered_view = NULL;
	
	///< An array of arguments to pass to the controller method from the URI.
	protected $_argv = array();
	
	///< The Artisan_Config configuration object passed into the router.
	protected $_controllerConfig = NULL;
	
	///< The directory that models are held in.
	const MODEL_DIR = 'Model';
	
	protected $_view_variable_list = array();
	
	/**
	 * Constructor for the controller.
	 * @author vmc <vmc@leftnode.com>
	 * @param $config An Artisan_Config object that holds directory information and default controller information.
	 * @param $method The method of the controller to execute.
	 * @param $argv An array of arguments to pass to the controller method.
	 * @retval Object Returns a new Artisan_Controller object.
	 */
	public function __construct(Artisan_Config $config, $method, $argv=array()) {
		$this->_controllerConfig = $config;
		
		$this->_method = $method;
		$this->_argv = (array)$argv;
		
		$this->view = new Artisan_Controller_View($config->root_dir);
		$this->view->setSiteRoot($config->site_root)
			->setSiteRootSecure($config->site_root_secure)
			->setIsRewrite($config->rewrite);
	}
	
	public function __destruct() {
		$this->_controllerConfig = NULL;
	}
	
	public function __set($name, $value) {
		$this->view->$name = $value;
	}
	
	public function __get($name) {
		return $this->view->$name;
	}
	
	public function setLayout($layout) {
		$this->_layout = $layout;
	}
	
	public function getLayout() {
		return $this->_layout;
	}
	
	public function load() {
		$ds = DIRECTORY_SEPARATOR;
		
		/**
		 * Methods are methodGet() or methodPost(). This allows
		 * different methods for different request types.
		 */
		$rm = ucwords(strtolower($_SERVER['REQUEST_METHOD']));
		$method = $this->_method.$rm;

		$artisanMethod = new ReflectionMethod($this, $method);

		$param_count = $artisanMethod->getNumberOfRequiredParameters();
		$argv = $this->_argv;
		$argc = count($this->_argv);
		if ( $param_count != $argc ) {
			if ( $param_count > $argc ) {
				$argv = array_pad($argv, $param_count, NULL);
			}
		}

		if ( true === $artisanMethod->isPublic() ) {
			if ( true === $artisanMethod->isStatic() ) {
				$artisanMethod->invokeArgs(NULL, $argv);
			} else {
				$artisanMethod->invokeArgs($this, $argv);
			}
		}

		$layout_file = $this->_controllerConfig->layout_dir . $ds . $this->_layout . LAYOUT_EXT;

		$content = $this->_rendered_view;
		if ( true === is_file($layout_file) ) {
			ob_start();
			require_once $layout_file;
			$content = ob_get_clean();
		}
		
		return $content;
	}
	
	
	public function render($name=NULL, $block_name=NULL) {
		$controller = $this->_getControllerName();
	
		if ( true === empty($name) ) {
			$view = $this->_method;
		} else {
			$name_bits = explode('/', $name);
			$len = count($name_bits);
			if ( 1 == $len ) {
				$view = current($name_bits);
			} else {
				$controller = asfw_rename_controller($name_bits[0]);
				$view = $name_bits[1];
			}
		}
		
		$view = asfw_rename_view($view);
		$this->_rendered_view = $this->view->render($controller, $view);
		
		if ( false === empty($block_name) ) {
			$this->setBlock($block_name, $this->_rendered_view);
		}
		
		return $this->_rendered_view;
	}
	
	public function buildValidator() {
		$ds = DIRECTORY_SEPARATOR;
		
		$controller = $this->_getControllerName();
		$model_file = $this->_controllerConfig->root_dir . $ds . $controller . $ds . self::MODEL_DIR . $ds . $controller . CONTROLLER_EXT;

		if ( false === is_file($model_file) ) {
			throw new Artisan_Controller_Exception('The Model Validator file ' . $model_file . ' could not be found.');
		}
		
		require_once $model_file;
		
		$model_validator = $controller . MODEL_VALIDATOR_SUFFIX;
		if ( false === class_exists($model_validator) ) {
			throw new Artisan_Controller_Exception('The class ' . $model_validator . ' can not be found.');
		}
		
		$model_validator_obj = new $model_validator();
		
		if ( false === $model_validator_obj instanceof Artisan_Controller_Model_Validator ) {
			throw new Artisan_Controller_Exception('The class ' . $model_validator . ' is not of type Artisan_Controller_Model_Validator.');
		}
		
		return $model_validator_obj;
	}
	
	public function getBlock($name) {
		if ( true === isset($this->_block_list[$name]) ) {
			return $this->_block_list[$name];
		}
		return NULL;
	}
	
	public function getParam($name) {
		$value = asfw_exists_return($name, $_REQUEST);
		return $value;
	}
	
	public function getFilesParam($name) {
		$value = asfw_exists_return($name, $_FILES);
		return $value;
	}
	
	public function setBlock($name, $value) {
		$this->_block_list[$name] = $value;
		return $this;
	}
	
	public function setLayoutDir($layout_dir) {
		$this->_layout_dir = rtrim($layout_dir, DIRECTORY_SEPARATOR);
	}
	
	public function url($url) {
		return $this->view->url($url);
	}
	
	protected function _getControllerName() {
		$class_name = get_class($this);
		$class_name = str_replace(CONTROLLER_SUFFIX, NULL, $class_name);
		$controller = asfw_rename_controller($class_name);
		return $controller;
	}
}