<?php

class Artisan_View {
	private $app_root_dir = NULL;
	private $is_rewrite = false;
	private $site_root = NULL;
	private $site_root_secure = NULL;
	private $view_dir = 'View';
	private $view_ext = '.phtml';
	private $auto_safe = false;
	private $validator = NULL;
	
	public function __construct($app_root_dir) {
		$this->setAppRootDir($app_root_dir);
	}
	
	public function __destruct() {
	}
	
	public function setAppRootDir($app_root_dir) {
		$this->app_root_dir = rtrim($app_root_dir, '/');
		return $this;
	}

	public function setIsRewrite($rewrite) {
		$this->is_rewrite = $rewrite;
		return $this;
	}
	
	public function setSiteRoot($site_root) {
		$this->site_root = $site_root;
		return $this;
	}
	
	public function setSiteRootSecure($site_root_secure) {
		$this->site_root_secure = $site_root_secure;
		return $this;
	}
	
	public function setAutoSafe($auto_safe) {
		$this->auto_safe = $auto_safe;
		return $this;
	}
	
	public function setValidator(Artisan_Validator $validator) {
		$this->validator = $validator;
		return $this;
	}
	
	public function setViewDir($view_dir) {
		$this->view_dir = $view_dir;
		return $this;
	}
	
	public function setViewExt($view_ext) {
		$this->view_ext = $view_ext;
		return $this;
	}

	public function render($controller, $view) {
		$ds = DIRECTORY_SEPARATOR;

		$view_file = $this->app_root_dir . $ds . $controller . $ds . $this->view_dir. $ds . $view . $this->view_ext;

		$rendered_view = NULL;
		
		if ( true === $this->auto_safe ) {
			foreach ( $this as $property ) {
				if ( true === is_string($property) ) {
					$this->$property = $this->safe($property);
				}
			}
		}
		
		extract(get_object_vars($this));
		if ( true === is_file($view_file) ) {
			ob_start();
			require $view_file;
			$rendered_view = ob_get_clean();
		}
		
		return $rendered_view;
	}
	
	public function safe($v) {
		return htmlentities($v, ENT_COMPAT, 'UTF-8');
	}
	
	public function css($css_file, $media = 'screen', $xhtml = false) {
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
	
	public function img($src, $alt = NULL, $attrs = NULL, $xhtml = false) {
		$img = '<img src="' . $src . '"';
		
		if ( false === empty($alt) ) {
			$img .= ' alt="' . $this->safe($alt) . '" ';
		}
		
		if ( false === empty($attrs) ) {
			$img .= ' ' . $attrs . ' ';
		}
		
		$end_tag = ( true === $xhtml ? ' />' : '>' );
		$img = $img . $end_tag . PHP_EOL;
		
		return $img;
	}
	
	public function href($url, $text, $attrs = NULL) {
		$text = $this->safe($text);
		$href = '<a href="' . $url . '" ' . $attrs . '>' . $text . '</a>' . PHP_EOL;
		return $href;
	}
	
	public function url() {
		$argc = func_num_args();
		$argv = func_get_args();
		
		$url = $this->makeUrl($argc, $argv);
		$url = $this->site_root . $url;
		
		return $url;
	}
	
	public function urls() {
		$argc = func_num_args();
		$argv = func_get_args();
		
		$url = $this->makeUrl($argc, $argv);
		$url = $this->site_root_secure . $url;
		
		return $url;
	}
	
	public function error($field) {
		if ( true === is_object($this->validator) ) {
			$error_list = $this->validator->getErrorList();
			return er($field, $error_list);
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
		if ( true === $this->is_rewrite ) {
			$url = $path;
		} else {
			$url = 'index.php?_u=' . $path;
		}
		
		return $url;
	}
}
