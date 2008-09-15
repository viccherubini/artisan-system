<?php

/**
 * Creates an Artisan_User without any external dependencies
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_User_Default extends Artisan_User {
	public function __construct() {
		
	}
	
	/**
	 * Default users can not be written anywhere, simply returns true.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Always returns true.
	 */
	public function write() {
		return true;
	}
	
	/**
	 * Nothing to load, returns true.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Always returns true.
	 */
	public function load($user_id) {
		
	}
	
	/**
	 * Nothing to load, returns true.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Always returns true.
	 */
	protected function _load($user_id) {

	}
	
	/**
	 * Nothing to insert, returns true.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Always returns true.
	 */
	protected function _insert() {
		return true;	
	}
	
	/**
	 * Nothing to update, returns true.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Always returns true.
	 */
	protected function _update() {
		return true;
	}
	
	protected function _makeRecord() {
		
	}
}

?>
