<?php

/**
 * The abstract Insert class for building a query to delete data from a database.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Sql_Delete extends Artisan_Sql {
	///< The main table the query is selecting FROM.
	protected $_from_table = NULL;
	
	///< The number of rows that should be deleted
	protected $_limit = 0;
	
	/**
	 * Default constructor for building a new INSERT query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object New Artisan_Sql_Delete object.
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
	 * Specifies what table to delete from.
	 * @author vmc <vmc@leftnode.com>
	 * @param $table The name of the table
	 * @throw Artisan_Sql_Exception If the table name is empty.
	 * @retval Object Returns itself for chainability.
	 */
	public function from($table) {
		$table = trim($table);
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL DELETE class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$this->_from_table = $table;
		
		return $this;
	}
	
	/**
	 * Sets the limit for the number of rows that should be deleted.
	 * @author vmc <vmc@leftnode.com>
	 * @param $limit Positive integer limit.
	 * @retval Object Returns itself for chainability.
	 */
	public function limit($limit) {
		$limit = abs(intval($limit));
		$this->_limit = $limit;
		
		return $this;
	}
	
	/**
	 * Builds the query to execute.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	abstract public function build();

	/**
	 * Executes the query against the database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns an instance of itself for chaining.
	 */
	abstract public function query();
	
	/**
	 * Returns the number of rows inserted.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int Returns the number of rows affected by the DELETE.
	 */
	abstract public function affectedRows();

	/**
	 * Escapes a string based on the character set of the current connection.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns a context escaped string.
	 */
	abstract public function escape($value);
}

?>
