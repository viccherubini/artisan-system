<?php

/**
 * The Sql_Update class for creating a Update statement to run against a database.
 * @author vmc <vmc@leftnode.com>
 * @todo Finish implementing this!
 */
abstract class Artisan_Sql_Update extends Artisan_Sql {
	///< The name of the table to be updated.
	protected $_table = NULL;
	
	///< The list of fields to update.
	protected $_update_field_list = array();
	
	/**
	 * Default constructor for building a new UPDATE query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object New Artisan_Sql_Update object.
	 */
	public function __construct() {
		$this->_sql = NULL;
	}
	
	/**
	 * Destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Destroys the object.
	 */
	public function __destruct() {
		unset($this->_sql);
	}
	
	/**
	 * Sets up what table and fields to update.
	 * @author vmc <vmc@leftnode.com>
	 * @param $table The name of the table to update.
	 * @param $update_fields An optional array of fields to update. This is built based on the number of parameters. If not specified, class assumes all fields will have an insert value.
	 * @throw Artisan_Sql_Exception If the table name is empty.
	 * @retval Object Returns an instance of itself to allow chaining.
	 */
	public function table($table) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL UPDATE class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$this->_table = $table;
		
		return $this;
	}
}
