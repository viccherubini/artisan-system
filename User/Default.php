<?php

/**
 * Creates an Artisan_User without any external dependencies
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_User_Default extends Artisan_User {
	public function __construct() {
		
	}
	
	
	public function write() {
		return true;
	}
	
	public function load($user_id) {
		
	}
	
	protected function _load($user_id) {

	}
	
	protected function _insert() {
		return true;	
	}
	
	protected function _update() {
		return true;
	}
	
	protected function _makeRecord() {
		
	}
}

?>
