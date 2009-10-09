<?php

require_once 'Artisan/Function/String.php';

class Artisan_Controller_View {
	///< The root of the application in which to load up controllers and views.
	private $_app_root_dir = NULL;
	private $_is_rewrite = false;
	private $_site_root = NULL;
	private $_site_root_secure = NULL;
	
	private $_validator = NULL;
	
	const VIEW_DIR = 'View';
	const VIEW_EXT = '.phtml';
	
	public function __construct($app_root_dir) {
		$this->setAppRootDir($app_root_dir);
	}
	
	public function __destruct() {
	}
	
	public function setAppRootDir($app_root_dir) {
		$this->_app_root_dir = rtrim($app_root_dir, '/');
		return $this;
	}

	public function setIsRewrite($rewrite) {
		$this->_is_rewrite = $rewrite;
		return $this;
	}
	
	public function setSiteRoot($site_root) {
		$this->_site_root = $site_root;
		return $this;
	}
	
	public function setSiteRootSecure($site_root_secure) {
		$this->_site_root_secure = $site_root_secure;
		return $this;
	}
	
	public function setValidator(Artisan_Controller_Model_Validator $validator) {
		$this->_validator = $validator;
		return $this;
	}

	public function render($controller, $view) {
		$ds = DIRECTORY_SEPARATOR;

		$controller = asfw_rename_controller($controller);
		$view_file = $this->_app_root_dir . $ds . $controller . $ds . self::VIEW_DIR . $ds . $view . self::VIEW_EXT;

		$rendered_view = NULL;
		extract(get_object_vars($this));
		if ( true === is_file($view_file) ) {
			ob_start();
			require $view_file;
			$rendered_view = ob_get_clean();
		}
		
		return $rendered_view;
	}
	
	public function safe($v) {
		return asfw_safe($v);
	}
	
	public function css($css_file, $media = 'screen', $xhtml = true) {
		if ( 0 == preg_match('/\.css$/i', $css_file) ) {
			$css_file .= '.css';
		}
		
		if ( true === empty($media) ) {
			$media = 'screen';
		}
		
		$end_tag = ( true === $xhtml ? ' />' : '>' );
		$link_tag = '<link type="text/css" rel="stylesheet" href="' . $css_file . '" media="' . $media . '"' . $end_tag . PHP_EOL;
		
		return $link_tag;
	}
	
	public function js($js_file) {
		if ( 0 == preg_match('/\.js$/i', $js_file) ) {
			$js_file .= '.js';
		}
		
		$js_tag  = '<script src="' . $js_file . '"></script>' . PHP_EOL;
		return $js_tag;
	}
	
	public function img($src, $alt=NULL, $attrs=NULL, $xhtml=true) {
		$img = '<img src="' . $src . '"';
		
		if ( false === empty($alt) ) {
			$img .= ' alt="' . $this->safe($alt) . '" ';
		}
		
		if ( false === empty($attrs) ) {
			$img .= ' ' . $attrs . ' ';
		}
		
		$end_tag = ( true === $xhtml ? ' />' : '>' );
		$img = $img . $end_tag;
		
		return $img;
	}
	
	
	public function url() {
		$argc = func_num_args();
		$argv = func_get_args();
		
		$url = $this->_makeUrl($argc, $argv);
		
		$url = $this->_site_root . $url;
		return $url;
	}
	
	public function urls() {
		$argc = func_num_args();
		$argv = func_get_args();
		
		$url = $this->_makeUrl($argc, $argv);
		
		$url = $this->_site_root_secure . $url;
		return $url;
	}
	
	public function error($field) {
		if ( true === is_object($this->_validator) ) {
			$error_list = $this->_validator->getErrorList();
			return asfw_exists_return($field, $error_list);
		}
		return NULL;
	}
	
	private function _makeUrl($argc, $argv) {
		if ( 0 == $argc ) {
			return NULL;
		}
		
		$param = NULL;
		$loc = $argv[0];
		if ( $argc > 1 ) {
			$argv = array_slice($argv, 1);
			$param = '/' . implode('/', $argv);
		}
		
		$path = $loc . $param;
		if ( true === $this->_is_rewrite ) {
			$url = $path;
		} else {
			$url = 'index.php?u=' . $path;
		}
		
		return $url;
	}
}