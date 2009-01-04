<?php

/**
 * @see Artisan_Db_Sql_Exception
 */
require_once 'Artisan/Db/Sql/Exception.php';

/**
 * @see Artisan_Db_Sql
 */
require_once 'Artisan/Db/Sql.php';

/**
 * The abstract Insert class for building a query to insert data into a database.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Db_Sql_Insert extends Artisan_Db_Sql {
	///< The main table the query is inserting INTO.
	protected $_into_table = NULL;
	
	///< The fields to insert data into, must be an associative array.
	protected $_insert_field_list = array();
	
	///< The list of values to insert into the fields.
	protected $_insert_field_value_list = array();

	///< If the INSERT query should be executed as a REPLACE query instead.
	protected $_is_replace = false;
	
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
	 * @param $insert_fields An optional array of fields to insert into.
	 *  This is built based on the number of parameters. If not specified, class assumes all fields will have an insert value.
	 * @retval Object Returns an instance of itself to allow chaining.
	 */
	public function into($table) {
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
			throw new Artisan_Db_Sql_Exception(ARTISAN_WARNING, 'The no values were passed into the method to insert.');
		}

		// See if only one argument was set and it's an array, if so
		// use that as the data rather than func_get_args()
		if ( 1 === $argc && true === is_array(func_get_arg(0)) ) {
			$arg = func_get_arg(0);
			
			// If this is an associative array, use the keys as the fields and values
			// as the values to insert.
			if ( true === asfw_is_assoc($arg) ) {
				$this->_insert_field_list = asfw_sanitize_field_list(array_keys($arg));
			}
			$this->_insert_field_value_list = array_values($arg);
		} else {
			$ifl_len = count($this->_insert_field_list);
			if ( $argc != $ifl_len && $ifl_len > 0 ) {
				$exception = 'The number of values to insert does not match the column count: ' . $argc . ' value(s) and ' . $ifl_len . ' column(s).';
				throw new Artisan_Db_Sql_Exception(ARTISAN_WARNING, $exception);
			}
			$this->_insert_field_value_list = func_get_args();
		}
		return $this;
	}
	
	public function setReplace($rep_type) {
		if ( true === is_bool($rep_type) ) {
			$this->_is_replace = $rep_type;
		} else {
			$this->_is_replace = false;
		}
		return true;
	}
	
	/**
	 * Builds the query to execute.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the built query.
	 */
	public function build() {
		$query_type = 'INSERT';
		if ( true === $this->_is_replace ) {
			$query_type = 'REPLACE';
		}
	
		$insert_sql = $query_type . " INTO `" . $this->_into_table . "`";
		
		$insert_field_sql = NULL;
		if ( count($this->_insert_field_list) > 0 ) {
			$insert_field_sql = " (" . implode(', ', $this->_insert_field_list) . ") ";
		}
		
		$value_list = array();
		$insert_value_sql = " VALUES (";
		foreach ( $this->_insert_field_value_list as $value ) {
			$value = $this->DB->escape($value);
			switch ( strtoupper($value) ) {
				case NULL: {
					$value_list[] = 'NULL';
					break;
				}
				
				case 'NULL': 
				case 'NOW()': {
					$value_list[] = $value;
					break;
				}
			
				default: {
					$value_list[] = "'" . $value . "'";
					break;
				}
			}
		}
		$insert_value_sql = " VALUES (" . implode(", ", $value_list) . ") ";

		$this->_sql = $insert_sql . $insert_field_sql . $insert_value_sql;
		return $this->_sql;
	}
}