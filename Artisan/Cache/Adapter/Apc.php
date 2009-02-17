<?php

/**
 * @see Artisan_Cache_Adapter
 */
require_once 'Artisan/Cache/Adapter.php';

/**
 * Allows for caching using the APC, Alternative PHP Cache. The APC is a PHP
 * module that needs to be installed for this to work as it does not come installed
 * with PHP by default.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Cache_Adapter_Apc extends Artisan_Cache_Adapter {
	/**
	 * Builds a new APC cache object. This is one exception where an exception
	 * is thrown in a constructor because it relies on an outside extension.
	 * @author vmc <vmc@leftnode.com>
	 * @param $defaultTtl The default TTL for all cache objects. This value can be overridden in the setTtlOverride() method.
	 * @throw Artisan_Cache_Exception If the APC extension is not loaded.
	 * @retval Object Returns new Artisan_Cache_Adapter_Apc instance.
	 */
	public function __construct($defaultTtl = 0) {
		$this->_defaultTtl = abs($defaultTtl);
		
		if ( false === extension_loaded('apc') ) {
			throw new Artisan_Cache_Exception(ARTISAN_ERROR, 'The APC extension is not loaded and thus, this can not be used.');
		}

		$this->_started = true;
	}

	/**
	 * Adds an element to the cache. The default TTL can be overwritten.
	 * @author vmc <vmc@leftnode.com>
	 * @param $id The ID of the element to add.
	 * @param $value The actual element to add.
	 * @param $ttl The time to live for that element.
	 * @retval boolean Returns true.
	 */
	public function add($id, $value, $ttl = 0) {
		$ttl = abs($ttl);
		if ( 0 == $ttl ) {
			$ttl = $this->_defaultTtl;
		}
		
		// See if this ID exists in the override table.
		if ( true === asfw_exists($id, $this->_ttlOverride) ) {
			$ttl = $this->_ttlOverride[$id];
		}
		
		if ( false === is_null($value) ) {
			apc_store($id, $value, $ttl);
		}
		return true;
	}
	
	/**
	 * Returns a cached element. It can be removed from the cache at the this time too.
	 * @author vmc <vmc@leftnode.com>
	 * @param $id The ID of the element to fetch.
	 * @param $purge Whether or not to delete the item from the cache. Defaults to false.
	 * @retval boolean Returns the item if it exists, NULL otherwise.s
	 */
	public function fetch($id, $purge = false) {
		$value = apc_fetch($id);
		if ( true === $purge ) {
			apc_delete($id);
		}
		return $value;
	}
	
	/**
	 * Returns if a cached element exists by it's ID.
	 * @author vmc <vmc@leftnode.com>
	 * @param $id The ID of the element to check existance for.
	 * @retval boolean Returns true if $id exists, false otherwise.
	 */
	public function exists($id) {
		// Ignore the value returned, and just set the value of $is_cached
		$is_cached = false;
		apc_fetch($id, $is_cached);
		return $is_cached;
	}
	
	/**
	 * Removes a specific cached element by it's ID.
	 * @author vmc <vmc@leftnode.com>
	 * @param $id The ID of the element to remove.
	 * @retval boolean Returns true.
	 */
	public function remove($id) {
		apc_delete($id);
		return true;
	}
	
	/**
	 * Erases the entire cache. This abstract method is defined in each actual Adapter.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function clear() {
		apc_clear_cache();
		return true;
	}
}