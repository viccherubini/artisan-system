<?php

abstract class Artisan_Sql_Delete extends Artisan_Sql {
	///< The actual SQL query in string form.
	protected $_sql = NULL;
	
	///< The main table the query is selecting FROM.
	protected $_from_table = NULL;
	
	///< The number of rows that should be deleted
	protected $_limit = 0;
	
	public function __construct() {
		$this->_sql = NULL;
	}
	
	public function __destruct() {
		unset($this->_sql, $this->_from_table, $this->_from_table_list);
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
		
		//$this->setFromTable($table);
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
	 * Resets all of the internal variables that make up a SQL query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function reset() {
		$this->setFromTable(NULL);
		$this->setFromTableAlias(NULL);
		$this->setWhereFieldList(array());
		
		return true;
	}
	
	public function __toString() {
		return $this->_sql;
	}
	
	abstract public function build();
	abstract public function query();
	abstract public function escape($value);
}

?>
