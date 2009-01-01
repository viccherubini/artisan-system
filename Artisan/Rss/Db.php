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
	 * Loads up the RSS data from the database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function load($urlizer) {
		$this->_checkDb();
		
		if ( false === function_exists($urlizer) ) {
			throw new Artisan_Rss_Exception(ARTISAN_WARNING, 'The method ' . $urlizer . '() does not exist.', __CLASS__, __FUNCTION__);
		}
		
		if ( false === $this->_map_set ) {
			throw new Artisan_Rss_Exception(ARTISAN_WARNING, 'The mapping has not been set up properly.', __CLASS__, __FUNCTION__);
		}
		
		if ( true === empty($this->_table) ) {
			throw new Artisan_Rss_Exception(ARTISAN_WARNING, 'The table to select data from is empty.', __CLASS__, __FUNCTION__);
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
		
		$LINK = new ReflectionFunction($urlizer);
		if ( $result_entry->numRows() > 0 ) {
			$m = $this->_map;
			while ( $entry = $result_entry->fetch() ) {
				$link_url = $LINK->invoke($entry);
				
				$item = array(
					'title' => $entry[$m['title']],
					'description' => $entry[$m['description']],
					'author' => $entry[$m['author']]
				);
			}
		}
	}
	
	/**
	 * Ensures that a database connection exists.
	 * @author vmc <vmc@leftnode.com>
	 * @param $method The method this is being called from.
	 * @throw Artisan_User_Exception If the database connection does not exist.
	 * @retval boolean Returns true.
	 */
	private function _checkDb($method) {
		if ( false === $this->DB->isConnected() ) {
			throw new Artisan_Auth_Exception(ARTISAN_WARNING, 'The database does not have an active connection.', __CLASS__, $method);
		}
		return true;
	}
}