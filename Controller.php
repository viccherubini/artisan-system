<?php

require_once 'Func.Library.php';

abstract class Artisan_Controller {
	public $view = NULL;
	
	protected $block_list = array();
	protected $method = NULL;
	protected $layout = NULL;
	protected $layout_dir = NULL;
	protected $layout_ext = '.phtml';
	protected $rendered_view = NULL;
	protected $argv = array();
	protected $config = array();
	protected $validator_dir = 'Model';
	protected $ext = '.php';
	protected $suffix = '_Controller';
	protected $validator_suffix = '_Validator';
	
	public function __construct($config, $method, $argv=array()) {
		$this->setConfig($config);
		
		$this->method = $method;
		$this->argv = (array)$argv;
		
		$this->view = new Artisan_View($config['root_dir']);
		$this->view->setSiteRoot($config['site_root'])
			->setSiteRootSecure($config['site_root_secure'])
			->setIsRewrite($config['rewrite']);
		
		$this->controller = $this->getControllerName();
	}
	
	public function __destruct() {
		$this->controllerConfig = NULL;
	}
	
	public function __set($name, $value) {
		$this->view->$name = $value;
	}
	
	public function __get($name) {
		return $this->view->$name;
	}
	
	public function setConfig($config) {
		$this->config = $config;
		return $this;
	}
	
	public function setLayout($layout) {
		$this->layout = $layout;
		return $this;
	}
	
	public function getLayout() {
		return $this->layout;
	}
	
	public function load() {
		$ds = DIRECTORY_SEPARATOR;
		
		/**
		 * Methods are methodGet() or methodPost(). This allows
		 * different methods for different request types.
		 */
		$rm = ucwords(strtolower($_SERVER['REQUEST_METHOD']));
		$method = $this->method.$rm;

		$refmethod = new ReflectionMethod($this, $method);

		$param_count = $refmethod->getNumberOfRequiredParameters();
		$argv = $this->argv;
		$argc = count($this->argv);
		if ( $param_count != $argc ) {
			if ( $param_count > $argc ) {
				$argv = array_pad($argv, $param_count, NULL);
			}
		}

		if ( true === $refmethod->isPublic() ) {
			if ( true === $refmethod->isStatic() ) {
				$refmethod->invokeArgs(NULL, $argv);
			} else {
				$refmethod->invokeArgs($this, $argv);
			}
		}

		$layout_file = $this->config['layout_dir'] . $ds . $this->layout . $this->layout_ext;
		$content = $this->rendered_view;
		
		if ( true === is_file($layout_file) ) {
			ob_start();
			require_once $layout_file;
			$content = ob_get_clean();
		}
		
		return $content;
	}
	
	public function render($name=NULL, $block_name=NULL) {
		$controller = $this->controller;
		
		if ( true === empty($name) ) {
			$view = $this->method;
		} else {
			$name_bits = explode('/', $name);
			$len = count($name_bits);
			if ( 1 == $len ) {
				$view = current($name_bits);
			} else {
				$controller = lib_rename_controller($name_bits[0]);
				$view = $name_bits[1];
			}
		}
		
		$view = lib_rename_view($view);
		$this->rendered_view = $this->view->render($controller, $view);
		
		if ( false === empty($block_name) ) {
			$this->setBlock($block_name, $this->rendered_view);
		}
		
		return $this->rendered_view;
	}
	
	public function buildValidator() {
		$ds = DIRECTORY_SEPARATOR;
		
		$validator_file = $this->config['root_dir'] . $ds . $this->controller . $ds . $this->validator_dir . $ds . $this->controller . $this->ext;

		if ( false === is_file($validator_file) ) {
			throw new Artisan_Exception("The Validator file '{$validator_file}' could not be found.");
		}
		
		require_once $validator_file;
		
		$validator = $this->controller . $this->validator_suffix;
		if ( false === class_exists($validator) ) {
			throw new Artisan_Exception("The class {$model_validator} can not be found.");
		}
		
		$validator = new $validator();
		
		if ( false === $validator instanceof Artisan_Validator ) {
			throw new Artisan_Exception("The class {$model_validator} is not of type Artisan_Validator.");
		}
		
		return $validator;
	}
	
	public function getBlock($name) {
		if ( true === isset($this->block_list[$name]) ) {
			return $this->block_list[$name];
		}
		return NULL;
	}
	
	public function getParam($name) {
		return er($name, $_REQUEST);
	}
	
	public function getFilesParam($name) {
		return er($name, $_FILES);
		return $value;
	}
	
	public function setBlock($name, $value) {
		$this->block_list[$name] = $value;
		return $this;
	}
	
	public function setLayoutDir($layout_dir) {
		$this->layout_dir = rtrim($layout_dir, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	public function url() {
		$argv = func_get_args();
		return $this->view->url(implode('/', $argv));
	}
	
	public function getControllerName() {
		$class_name = get_class($this);
		$class_name = str_replace($this->suffix, NULL, $class_name);
		$controller = lib_rename_controller($class_name);
		return $controller;
	}
}