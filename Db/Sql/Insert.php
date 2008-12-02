<?php

require_once 'Artisan/Db/Sql/Exception.php';

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
	 * @param $insert_fields An optional array of fields to insert into. This is built based on the number of parameters. If not specified, class assumes all fields will have an insert value.
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
			throw new Artisan_Db_Sql_Exception(ARTISAN_WARNING, 'The no values were passed into the method to insert.', __CLASS__, __FUNCTION__);
		}

		// See if only one argument was set and it's an array, if so
		// use that as the data rather than func_get_args()
		if ( 1 === $argc && true === is_array(func_get_arg(0)) ) {
			$arg = func_get_arg(0);
			if ( true === asfw_is_assoc($arg) ) {
				$this->_insert_field_list = asfw_sanitize_field_list(array_keys($arg));
			}
			
			$this->_insert_field_value_list = array_values($arg);
		} else {
			$ifl_len = count($this->_insert_field_list);
			if ( $argc != $ifl_len && $ifl_len > 0 ) {
				throw new Artisan_Db_Sql_Exception(ARTISAN_WARNING, 
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
	public function build() {
		$insert_sql  = "INSERT INTO `" . $this->_into_table . "`";
		
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
		
		return true;
	}
}