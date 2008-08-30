<?php


Artisan_Library::load('Template/Monitor');
Artisan_Library::load('Template/Exception');

abstract class Artisan_Template {
	protected static $_config = NULL;
	
	public function __construct() {
		
	}

	
	abstract public function setTheme($theme);
	abstract public function load($template);
	
	
	public function setVariables() {
	
	}
	
	
	public function getCache() {
	
	}
	
	private function _parse() {
	
	
	}
}

?>
