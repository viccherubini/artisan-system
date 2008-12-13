<?php


class Artisan_View {
	private $_theme_dir = NULL;
	private $_theme_name = NULL;
	private $_view_dir = NULL;

	public function __construct() {

	}

	public function setViewDir($view_dir) {
		$this->_view_dir = trim($view_dir);
	}

	public function setThemeDir($theme_dir) {
		$this->_theme_dir = trim($theme_dir);
	}

	public function setTheme($theme_name) {
		$this->_theme_name = $theme_name;
	}

	public function getThemeCss($css_name) {
		return ( $this->_theme_dir . DIRECTORY_SEPARATOR . $this->_theme_name . DIRECTORY_CSS . $css_name . '.css' );
	}

	public function view($view_name) {
		
	}
}
