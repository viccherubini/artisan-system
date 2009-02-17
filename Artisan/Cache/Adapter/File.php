<?php


class Artisan_Cache_Adapter_File extends Artisan_Cache_Adapter {
	private $_cacheLoc;
	private $_collisionAllow = false;
	
	public function __construct($cacheLoc, $collisionAllow, $defaultTtl) {
		
	}

	public function add($id, $value, $ttl=0) {
	
	}
	
	public function exists($id) {
	
	}
	
	
	public function remove($id) {
	
	}
	
	public function fetch($id, $purge = false) {
		
	}
	
	public function clear() {
		
	}

}