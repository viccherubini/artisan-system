<?php

/**
 * @see Artisan_Db_Sql
 */
require_once 'Artisan/Db/Sql.php';

/**
 * The abstract Insert class for building a query to delete data from a database.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Db_Sql_Delete extends Artisan_Db_Sql {
	///< The main table the query is selecting FROM.
	protected $_from_table = NULL;
	
	///< The number of rows that should be deleted
	protected $_limit = 0;
	
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
	 * @retval Object Returns itself for chainability.
	 */
	public function from($table) {
		$table = trim($table);
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
	 * Builds the correct DELETE query string.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the built query.
	 */
	public function build() {
		$where_sql = $this->buildWhereClause($this->_where_field_list);
		
		$limit_sql = NULL;
		if ( $this->_limit > 0 ) {
			$limit_sql = " LIMIT " . $this->_limit;
		}
		
		$delete_start = "DELETE FROM `" . $this->_from_table . "` ";
		$this->_sql = $delete_start . $where_sql . $limit_sql;
		
		return $this->_sql;
	}
}