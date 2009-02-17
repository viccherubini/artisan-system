<?php


/**
 * This is a Controller class as it controls the different views.
 * By default, each view name is the same name as the method it's
 * being called from unless explicitly specified by setting $this->_view.
 */
class Admin extends Artisan_Controller_View {
	// Use the default layout. Will search in public/Admin/layout/default.phtml first,
	// if that is not found, then it will search in public/layout/default.phtml. If that isn't
	// found, then an exception is thrown.
	protected $_layout = 'default';
	protected $_view = NULL;
	
	public function index() {
		$this->title = 'Welcome to the Artisan System PHP Framework';
	}
	
	public function configuration() {
		$this->title = 'Editing Configuration';
	}
	
	public function users() {
		$this->title = 'Editing Users';
	}
	
	public function controllers() {
		$this->title = 'Editing Controllers';
	}
}