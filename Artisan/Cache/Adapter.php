<?php

/**
 * @see Artisan_Cache_Exception
 */
require_once 'Artisan/Cache/Exception.php';

/**
 * The adapter class builds a cache to operate on cache data in a consistent manner.
 * The easiest Adapter to use is the APC, however, on systems where no caching is
 * available, the Session or File are all written in the basic PHP library.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Cache_Adapter {
	/// The default TTL is to allow all cache members to live infinitely.
	protected $_defaultTtl = 0;
	
	/// Provides an array of key/value pairs to overwrite the default TTL for a specific view. See setTtlOverride() for more details.
	protected $_ttlOverride = array();
	
	/// Whether or not the caching system has been initialized.
	protected $_started = false;

	/**
	 * Returns if a cached element exists by it's ID. This abstract method is defined in each actual Adapter.
	 * @author vmc <vmc@leftnode.com>
	 * @param $id The ID of the element to check existance for.
	 * @retval boolean Returns true if $id exists, false otherwise.
	 */
	abstract public function exists($id);
	
	/**
	 * Adds an element to the cache. The default TTL can be overwritten. This abstract method is defined in each actual Adapter.
	 * @author vmc <vmc@leftnode.com>
	 * @param $id The ID of the element to add.
	 * @param $value The actual element to add.
	 * @param $ttl The time to live for that element.
	 * @retval boolean Returns true if $id was successfully added, false otherwise.
	 */
	abstract public function add($id, $value, $ttl = 0);
	
	/**
	 * Removes a specific cached element by it's ID. This abstract method is defined in each actual Adapter.
	 * @author vmc <vmc@leftnode.com>
	 * @param $id The ID of the element to remove.
	 * @retval boolean Returns true.
	 */
	abstract public function remove($id);
	
	/**
	 * Returns a cached element. It can be removed from the cache at the this time too. This abstract method is defined in each actual Adapter.
	 * @author vmc <vmc@leftnode.com>
	 * @param $id The ID of the element to fetch.
	 * @param $purge Whether or not to delete the item from the cache. Defaults to false.
	 * @retval boolean Returns the item if it exists, NULL otherwise.s
	 */
	abstract public function fetch($id, $purge = false);
	
	/**
	 * Erases the entire cache. This abstract method is defined in each actual Adapter.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	abstract public function clear();
	
	/**
	 * Allow specific TTL's for views provided in here, this
	 * allows for views that have commonly updated data to have a
	 * different TTL than the default.
	 * @code
	 * $override = array(
	 *   'Artisan_index' => 15,
	 *   'Blog_entry'    => 5,
	 *   'Blog_index'    => 100
	 * );
	 * @endcode
	 * Thus, each key should be in the format of Controller_view. Of course, this
	 * only works if you are using the Artisan MVC/View classes.
	 * @author vmc <vmc@leftnode.com>
	 * @param $override The override array. See the code section for how this should look.
	 */
	public function setTtlOverride($override) {
		
	}

	/**
	 * Returns if the caching adapter has started caching data yet.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if the cache has started, false otherwise.
	 */
	public function started() {
		return $this->_started;
	}
}