<?php

Artisan_Library::load('Template/Exception');

class Artisan_Template_Database extends Artisan_Template {

	public function __construct($config = array()) {
		echo 'In template::database constructor<br />';
	}

	public function __destruct() {
		echo 'template::database dying<br />';
	}

	public function load($template_name) {
		//throw new Artisan_Template_Exception(1, 'Some error occurred in loading the template', __CLASS__, __FUNCTION__);

		echo 'loading "<strong>' . $template_name . '</strong>" in template::database::load()<br />';

		$db = Artisan_Database_Monitor::get();
		$db->query('Select * from template where template_id = 1');
	}
}

?>
