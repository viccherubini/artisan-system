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
	 * @code
	 * // Example:
	 * $db->update->table('table_name', array('field1' => 'value1', 'field2' => 'value2'))->where('id = ?', $id)->query();
	 * @endcode
	 * @param $table The name of the table to update.
	 * @throw Artisan_Sql_Exception If the table name is empty.
	 * @retval Object Returns an instance of itself to allow chaining.
	 */
	public function table($table, $field_list) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL UPDATE class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$this->_table = $table;
		
		if ( false === is_array($field_list) || count($field_list) < 1 ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'At least one field must be specified to be updated.', __CLASS__, __FUNCTION__);
		}
		
		$this->_update_field_list = $field_list;
		
		return $this;
	}
}
