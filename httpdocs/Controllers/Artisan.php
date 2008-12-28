<?php

class Artisan extends Artisan_Controller_View {
	protected $_layout = 'default';
	protected $_view = NULL;
	
	public function index() {
		$this->title = 'Welcome to Artisan System';
		$this->text = '';
	}
}