<?php

require_once 'Func.Library.php';

/**
 * The Artisan_Router class handles routing URI's to their proper Controller
 * and Method. URI's are in the format of http://artisansystem.com/user/create/var1/var2/var3
 * 
 * Without the .htaccess file, the previous URI would be translated to:
 * http://artisansystem.com/index.php?_u=user/create/var1/var2/var3
 * 
 * In this case, the class User_Controller would be loaded, the createGet()
 * method would be executed, with three parameters with the values var1,
 * var2, and var3. Thus, createGet() would be written as:
 * User_Controller::createGet($var1, $var2, $var3) { }
 * 
 * Note that the variable names can be anything you want.
 * 
 * @author vmc <vmc@leftnode.com>
 * @see Func.Library.php
 * @see Artisan_Controller
 * @see Artisan_View
 */
class Artisan_Router {
	private $class = NULL;
	private $method = NULL;
	private $controller = NULL;
	private $argv = array();
	private $config = array();
	private $alias = array();
	private $ext = '.php';
	private $suffix = '_Controller';
	
	/**
	 * Default constructor to build a new router.
	 * 
	 * @param $config
	 *    The configuration array for the router.
	 * @see setConfig()
	 */
	public function __construct(array $config) {
		$this->setConfig($config);
	}

	public function __destruct() {
		unset($this->class, $this->method, $this->controller, $this->config, $this->argv);
	}
	
	/**
	 * Sets the configuration for the router.
	 * 
	 * @param $config
	 *     An array that holds the Router configuration. Must be in the following format:
	 * @code
	 * array(
	 *   'site_root' => 'http://artisansystem.com/',
	 *   'site_root_secure' => 'https://artisansystem.com/',
	 *   'root_dir' => 'application',
	 *   'layout_dir' => '/public/layout',
	 *   'default_controller' => 'Index',
	 *   'default_method' => 'index',
	 *   'default_layout' => 'index',
	 *   'rewrite' => true
	 * );
	 * @endcode
	 */
	public function setConfig(array $config) {
		$this->config = $config;
		return $this;
	}

	/**
	 * Sets the alias routing table.
	 * 
	 * @param $alias
	 *    The routing table to set. Should be in the format:
	 * @code
	 * array(
	 *   'new-route' => 'controller/method',
	 *   'can/use/replacements/%d' => 'user/view/%d'
	 * )
	 * @endcode
	 * 
	 * @retval Artisan_Router
	 *    Returns this for chaining.
	 */
	public function setAlias(array $alias) {
		$this->alias = $alias;
		return $this;
	}
	
	/**
	 * This method actually routes the URI to the appropriate controller and method from
	 * the _u GET variable. This allows an application to be execute from any
	 * directory while still having clean URL's.
	 * 
	 * @throw Artisan_Exception
	 *    Throws an exception if the controller file can not be found.
	 * 
	 * @retval string
	 *    Returns the rendered view from the controller, and the layout if included.
	 */
	public function dispatch() {
		$ds = DIRECTORY_SEPARATOR;
		
		$uri = er('_u', $_REQUEST);
		$uri = trim($uri, '/');
		
		$uri_bits = array();
		if ( false === empty($uri) ) {
			$uri_bits = explode('/', $uri);
		}
		
		/**
		 * Element 0 is the Controller, element 1 is the Method.
		 * If either of these can't be found, the defaults from $config are used.
		 * Any bit after that are the parameters to pass to the method.
		 */
		$controller = @$uri_bits[0];
		if ( false === isset($uri_bits[0]) ) {
			$controller = $this->config['default_controller'];
		}
		
		$controller = strtolower(trim($controller));
	
		/* Strip out non-ascii characters. */
		$controller = lib_rename_controller($controller);
		
		$this->controller = $controller;
		$this->file = $controller . $this->ext;
		$this->class = $controller . $this->suffix;
		
		$method = @$uri_bits[1];
		if ( false === isset($uri_bits[1]) ) {
			$method = $this->config['default_method'];
		}
		
		$method = lib_rename_method($method);
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
