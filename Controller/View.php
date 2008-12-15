<?php

require_once 'Artisan/Controller.php';

abstract class Artisan_Controller_View {
	private $_views_dir = 'Views';
	private $_layout_dir = 'Layout';
	
	private $_controller_dir = NULL;
	private $_ext = '.phtml';
	
	protected $_view = NULL;
	
	protected $layout_content = NULL;
	
	//public function __construct($cdir) {
	//	$this->_controller_dir = trim($cdir);
	//}
	
	public function __setControllerDirectory($cdir) {
		$this->_controller_dir = trim($cdir);
	}
	
	public function __setViewsDirectory($vdir) {
		$this->_views_dir = trim($vdir);
	}
	
	public function __setLayoutDirectory($ldir) {
		$this->_layout_dir = trim($ldir);
	}
	
	public function __execute($controller, $view) {
		$ds = '/';
		if ( true === defined('DIRECTORY_SEPARATOR') ) {
			$ds = DIRECTORY_SEPARATOR;
		}
	
		// This function is responsible for loading up the
		// appropriate method and executing the values.
		// $_layout_content will be set in here, and
		// because the Controller classes extend this class,
		// this class will have access to their values
		
		if ( false === empty($this->_view) ) {
			$view = $this->_view;
		}
		
		$controller = asfw_rename_controller($controller);
		$view = asfw_rename_controller_method($view);


		// See if Controllers/$controller/Views/$view.phtml exists, if not, 
		// look in Controllers/Views/$view.phtml. If that doesn't exist, throw an error.
		$view_file = $this->_controller_dir . $ds . $controller . $ds . $this->_views_dir . $ds . $view . $this->_ext;
		if ( false === is_file($view_file) ) {
			$view_file = $this->_controller_dir . $ds . $this->_views_dir . $ds . $view . $this->_ext;
		}
		
		if ( false === is_file($view_file) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'The view file ' . $view_file . ' does not exist.', __CLASS__, __FUNCTION__);
		}
		
		// See if Controllers/$controller/Layout/$layout.phtml exists, if not, 
		// look in Controllers/Layout/$layout.phtml. If that doesn't exist, throw an error.
		$layout_file = $this->_controller_dir . $ds . $controller . $ds . $this->_layout_dir . $ds . $this->_layout . $this->_ext;
		if ( false === is_file($layout_file) ) {
			$layout_file = $this->_controller_dir . $ds . $this->_layout_dir . $ds . $this->_layout . $this->_ext;
		}

		if ( false === is_file($layout_file) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'The layout file ' . $layout_file . ' does not exist.', __CLASS__, __FUNCTION__);
		}
		
		// First load up the view
		ob_start();
		require_once $view_file;
		$this->layout_content = ob_get_clean();
		
		// Now load up the layout
		ob_start();
		require_once $layout_file;
		return ob_get_clean();
	}
}