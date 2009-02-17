<?php

require_once 'Artisan/Db/Result/Aggregate.php';

/**
 * The abstract class for creating a result after a successful SELECT (or any other
 * query that returns data) query.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Db_Result {
	public $_aggResultList = array();
	
	private $_aggregate = NULL;
	
	private $_filter = NULL;
	
	/**
	 * Sets the internal row pointer to $offset if $offset is less than the number of
	 * rows returned.
	 * @author vmc <vmc@leftnode.com>
	 * @param $offset The row to point to.
	 * @retval boolean Returns true.
	 */
	abstract public function row($offset);
	
	/**
	 * Returns an array of data from the query. If $field is not null, and found in the
	 * result array, just that specific data is returned.
	 * @author vmc <vmc@leftnode.com>
	 * @param $field If not null, and found in the result, the specific field to return.
	 * @retval Returns an associative array from the query, or a single value if $field is found.
	 */
	abstract public function fetch($field = NULL);
	
	/**
	 * Fetches a Value Object from the result.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns a Value Object representation of the associative array.
	 */
	abstract public function fetchVo();
	
	/**
	 * Returns all data, with each row as an array, from a query. Be warned that
	 * this can return a lot of data if the query returns a lot.
	 * @author vmc <vmc@leftnode.com>
	 * @param $key_on_primary If true, all keys of the returned array will have the value of the primary key, otherwise
	 * they will be the index of the array.
	 * @todo Finish implemented $key_on_primary.
	 */
	abstract public function fetchAll($key_on_primary = false);
	
	/**
	 * Returns all data as a 2D array with each array value being a Value Object representation
	 * of the associative array row.
	 * @author vmc <vmc@leftnode.com>
	 * @retval array A 2D array of all fields matching the result query.
	 */
	abstract public function fetchAllVo();
	
	/**
	 * Frees memory from the result.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	abstract public function free();
	
	/**
	 * Returns the number of rows from the SELECT query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int Returns the number of rows from the query.
	 */
	abstract public function numRows();
	
	public function aggregate($agg, $field, $result_variable = NULL) {
		if ( false === is_object($this->_aggregate) ) {
			$this->_aggregate = new Artisan_Db_Result_Aggregate($this->fetchAll());
		}
		
		$result_variable = ( !empty($result_variable) ? $result_variable : $field );
		
		$this->_aggResultList[$result_variable][$agg] = $this->_aggregate->$agg($field);
		return $this;
	}
	
	public function filter($filter, $field) {
		
	}
}