<?php

class Artisan_Cache {
	private $defaultTtl = 0;
	private $ttlOverride = array();
	private $started = false;

	public function __construct($defaultTtl = 0) {
		$this->defaultTtl = abs($defaultTtl);
		
		if ( false === extension_loaded('apc') ) {
			throw new Artisan_Exception('The APC extension is not loaded and thus, this can not be used.');
		}

		$this->started = true;
	}

	public function add($id, $value, $ttl = 0) {
		$ttl = abs($ttl);
		if ( 0 == $ttl ) {
			$ttl = $this->defaultTtl;
		}
		
		// See if this ID exists in the override table.
		if ( true === isset($this->ttlOverride[$id]) ) {
			$ttl = $this->ttlOverride[$id];
		}
		
		if ( false === is_null($value) ) {
			apc_store($id, $value, $ttl);
		}
		return true;
	}
	
	public function fetch($id, $purge = false) {
		$value = apc_fetch($id);
		if ( true === $purge ) {
			apc_delete($id);
		}
		return $value;
	}
	
	public function exists($id) {
		// Ignore the value returned, and just set the value of $is_cached
		$is_cached = false;
		apc_fetch($id, $is_cached);
		return $is_cached;
	}
	
	public function remove($id) {
		apc_delete($id);
		return true;
	}
	
	public function clear() {
		apc_clear_cache();
		return true;
	}
}