<?php


/**
 * This is a Controller class as it controls the different views.
 * By default, each view name is the same name as the method it's
 * being called from unless explicitly specified by setting $this->_view.
 */
class Artisan extends Artisan_Controller_View {
	// Use the default layout. Will search in Controllers/Artisan/layout/default.phtml first,
	// if that is not found, then it will search in Controllers/layout/default.phtml. If that isn't
	// found, then an exception is thrown.
	protected $_layout = 'default';
	protected $_view = NULL;
	
	public function index() {
		$this->title = 'Welcome to Artisan System Framework 0.2';
	}
}
