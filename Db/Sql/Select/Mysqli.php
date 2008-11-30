<?php

/**
 * @see Artisan_Db_Exception
 */
require_once 'Artisan/Db/Exception.php';

/**
 * @see Artisan_Db_Result_Mysqli
 */
require_once 'Artisan/Db/Result/Mysqli.php';

/**
 * @see Artisan_Db_Sql_Select
 */
require_once 'Artisan/Db/Sql/Select.php';


/**
 * This is the final class that builds the actual Mysql SELECT query to be executed.
 * @author <vmc@leftnode.com>
 */
class Artisan_Db_Sql_Select_Mysqli extends Artisan_Db_Sql_Select {
	///< Database connection object.
	private $CONN = NULL;
	
	/**
	 * Builds a new SELECT clause.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns new Artisan_Db_Sql_Select_Mysqli object.
	 */
	public function __construct(mysqli &$CONN) {
		$this->CONN = $CONN;
		$this->_sql = NULL;
	}
	
	public function query() {
		// build() is defined in Artisan_Db_Sql_Select
		$this->build();
		
		if ( true === empty($this->_sql) ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'The SQL query is empty.', __CLASS__, __FUNCTION__);
		}
		
		$result = $this->CONN->query($this->_sql);
		if ( true === is_object($result) && true === $result instanceof mysqli_result ) {
			return new Artisan_Db_Result_Mysqli($result);
		}
		
		return NULL;
	}
	
	public function escape($value) {
		return $this->CONN->real_escape_string($value);
	}
}