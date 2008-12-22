<?php

/**
 * The abstract class for creating a result after a successful SELECT (or any other
 * query that returns data) query.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Db_Result {
	/**
	 * Sets the internal row pointer to $offset if $offset is less than the number of
	 * rows returned.
	 * @author vmc <vmc@leftnode.com>
	 * @param $offset The row to point to.
	 */
	abstract public function row($offset);
	
	/**
	 * Returns an array of data from the query. If $field is not null, and found in the
	 * result array, just that specific data is returned.
	 * @author vmc <vmc@leftnode.com>
	 * @param $field If not null, and found in the result, the specific field to return.
	 */
	abstract public function fetch($field = NULL);
	
	/**
	 * 
	 * @author vmc <vmc@leftnode.com>
	 * @param $key_on_primary
	 * @todo Finish implemented $key_on_primary.
	 */
	abstract public function fetchAll($key_on_primary = false);
	
	/**
	 *
	 * @author vmc <vmc@leftnode.com>
	 */
	abstract public function free();
	
	/**
	 *
	 * @author vmc <vmc@leftnode.com>
	 */
	abstract public function numRows();
}