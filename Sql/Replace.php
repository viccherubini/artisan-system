<?php

/**
 * The abstract Insert class for building a query to insert data into a database.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Sql_Replace extends Artisan_Sql {
	///< The main table the query is inserting INTO.
	protected $_into_table = NULL;
	
	///< The fields to insert data into, must be an associative array.
	protected $_insert_field_list = array();
	
	///< The list of values to insert into the fields.
	protected $_insert_field_value_list = array();
	
	/**
	 * Default constructor for building a new INSERT query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object New Artisan_Sql_Insert object.
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
	 * Sets up what table and fields to insert into.
	 * @author vmc <vmc@leftnode.com>
	 * @param $table The name of the table to insert into.
	 * @param $insert_fields An optional array of fields to insert into. If not specified, class assumes all fields will have an insert value.
	 * @retval Object Returns an instance of itself to allow chaining.
	 */
	public function into($table) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL INSERT class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$table = trim($table);
		$this->_into_table = $table;
		
		// Determine if the insert fields are listed or just an array
		$insert_fields = array();
		$argc = func_num_args();
		if ( $argc > 1 ) {
			$argv = func_get_args();
			array_shift($argv); // Remove the table name
			
			if ( true === is_array($argv[0]) && 2 === $argc ) {
				$insert_fields = $argv[0];
			} else {
				$insert_fields = $argv;
			}
		}
		
		$this->_insert_field_list = asfw_sanitize_field_list($insert_fields);

		return $this;
	}

	/**
	 * Takes a list of values to insert into the database. The way this method handles arguments is a bit tricky.
	 * If a single argument is passed to it, and it is an array, the method assumes it is a list of values to attempt to insert.
	 * If a single argument is passed to it, and it is not an array, the method assumes it is just a value to insert.
	 * Otherwise, if multiple values are sent, each one should match up to one of the fields.
	 * @author vmc <vmc@leftnode.com>
	 * @param $variable See method description for this parameters.
	 * @retval Object Returns an instance of itself for chaining.
	 */
	public function values() {
		$argc = func_num_args();
		if ( 0 === $argc ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The no values were passed into the method to insert.', __CLASS__, __FUNCTION__);
		}

		// See if only one argument was set and it's an array, if so
		// use that as the data rather than func_get_args()
		if ( 1 === $argc && true === is_array(func_get_arg(0)) ) {
			$this->_insert_field_value_list = func_get_arg(0);
		} else {
			$ifl_len = count($this->_insert_field_list);
			if ( $argc != $ifl_len && $ifl_len > 0 ) {
				throw new Artisan_Sql_Exception(ARTISAN_WARNING, 
					'The number of values to insert does not match the column count: ' . $argc . ' value(s) and ' . $ifl_len . ' column(s).',
					__CLASS__, __FUNCTION__
				);
			}

			$this->_insert_field_value_list = func_get_args();
		}
		
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
	 * @retval int Returns the number of rows affected by the INSERT.
	 */
	abstract public function affectedRows();

	/**
	 * Escapes a string based on the character set of the current connection.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns a context escaped string.
	 */
	abstract public function escape($value);
}
