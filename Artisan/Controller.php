<?php

class Artisan_Controller {
	public function __construct() {

	}


	public function view($view_name) {
		if ( true === empty($view_name) ) {
			return NULL;
		}

		// See if the view exists in the directory
		//$view_file = 'View/' . ucwords( str_replace('_', ' ', $view_name) ) . '.php';

		$view = NULL;
		if ( true === file_exists($view_file) ) {
			require_once $view_file;

			$view_class = ucwords( str_replace('_', ' ', $view_name) );

			$view = new $view_class();
		}

		return $view;
	}

}

?>