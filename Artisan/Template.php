<?php


Artisan_Library::load('Template/Monitor');

abstract class Artisan_Template {

	public function __construct($config = array()) {
		echo 'In template constructor<br />';
	}

	abstract public function load($template_name);
}

?>
