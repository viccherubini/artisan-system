<?php

/**
 * @see Artisan_Rss
 */
require_once 'Artisan/Rss.php';

/**
 * Loads data for an RSS feed from a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Rss_Db extends Artisan_Rss {
	///< The Database instance, must have an active connection.
	protected $DB = NULL;
	
	///< The name of the table to load data from.
	private $_table = NULL;
	
	/**
	 * Builds a new object to load in RSS data from a database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $DB The Database instance, must have an active connection.
	 * @retval Object A new Artisan_Rss_Db object.
	 */
	public function __construct(Artisan_Db &$DB) {
		$this->DB = &$DB;
	}
	
	/**
	 * Sets the name of the table to build the feed from.
	 * @author vmc <vmc@leftnode.com>
	 * @param $table The name of the table.
	 * @retval boolean Returns true.
	 */
	public function setTable($table) {
		$this->_table = trim($table);
		return true;
	}
	
	/**
	 * Loads up the RSS data from the database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function load($hook) {
		$this->_checkDb();
		
		if ( false === function_exists($hook) ) {
			throw new Artisan_Rss_Exception(ARTISAN_WARNING, 'The method ' . $hook . '() does not exist.');
		}
		
		if ( false === $this->_map_set ) {
			throw new Artisan_Rss_Exception(ARTISAN_WARNING, 'The mapping has not been set up properly.');
		}
		
		if ( true === empty($this->_table) ) {
			throw new Artisan_Rss_Exception(ARTISAN_WARNING, 'The table to select data from is empty.');
		}
		
		// Grab the date field
		$date_field = $this->_map['pubDate'];
		
		try {
			$result_entry = $this->DB->select()
				->from($this->_table)
				->orderBy($date_field, 'DESC')
				->query();
		} catch ( Artisan_Db_Exception $e ) {
			throw $e;
		}
		
		$LINK = new ReflectionFunction($hook);
		if ( $result_entry->numRows() > 0 ) {
			$map_values = array_values($this->_map);
			$map_keys = array_keys($this->_map);
			while ( $entry = $result_entry->fetch() ) {
				// First run the entry through the hook to add any additional keys or
				// operate on the data itself.
				$entry = $LINK->invoke($entry);
				
				// Next, create an array only of the data from the database that we need,
				// in other words, only the fields specified in the $_map.
				$entry = asfw_array_slice_keys($map_values, $entry);

				// Finally, build the $item array with the keys of the map as the keys
				// and the values of the entry as the values. This *only* works as a result
				// of the array built in asfw_array_slice_keys() is in the same order (keywise)
				// as the keys of the map!
				$item = array_combine($map_keys, $entry);
				$this->addItem(new Artisan_Vo($item));
			}
		}
		return true;
	}
	
	/**
	 * Ensures that a database connection exists.
	 * @author vmc <vmc@leftnode.com>
	 * @param $method The method this is being called from.
	 * @throw Artisan_User_Exception If the database connection does not exist.
	 * @retval boolean Returns true.
	 */
	private function _checkDb() {
		if ( false === $this->DB->isConnected() ) {
			throw new Artisan_Auth_Exception(ARTISAN_WARNING, 'The database does not have an active connection.');
		}
		return true;
	}
}