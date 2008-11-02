<?php

/**
 * Creates an Artisan_User without any external dependencies. In other words,
 * it simply creates a user within memory that can be used for manipulation.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_User_Default extends Artisan_User {
	/**
	 * Constructor for the Artisan_User class to store users.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object The new Artisan_User_Default object.
	 */
	public function __construct() { }
	
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
		return true;
	}
	
	/**
	 * Nothing to load, returns true.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Always returns true.
	 */
	protected function _load($user_id) {
		return true;
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
	
	/**
	 * No record to make, returns true.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Always returns true.
	 */
	protected function _makeRecord() {
		return true;
	}
}
