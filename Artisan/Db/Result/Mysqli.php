<?php

require_once 'Artisan/Functions/Array.php';

/**
 * @see Artisan_Db_Result
 */
require_once 'Artisan/Db/Result.php';

/**
 * @see Artisan_Vo
 */
require_once 'Artisan/Vo.php';

/**
 * Creates a new result object after a successful SELECT statement.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Db_Result_Mysqli extends Artisan_Db_Result {
	///< The result object from the database driver.
	private $RESULT = NULL;

	/**
	 * Builds a new result object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $RES The result object from the mysqli driver.
	 * @retval Object A new Artisan_Db_Result_Mysqli object.
	 */
	public function __construct(mysqli_result &$RES) {
		$this->RESULT = &$RES;
	}
	
	/**
	 * Sets the internal result pointer to the $offset specified.
	 * @author vmc <vmc@leftnode.com>
	 * @param $offset The row to go to. If this exceeds the number of rows, or is negative, it stays at the first row.
	 * @retval boolean Returns true.
	 */
	public function row($offset) {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			$offset = abs($offset);
			$row_count = $this->numRows();
			if ( $offset < $row_count ) {
				$this->RESULT->data_seek($offset);
			}
		}
		return true;
	}

	/**
	 * Fetches an associative array from the result if there are more rows to fetch.
	 * @author vmc <vmc@leftnode.com>
	 * @param $field An optional argument that if set, and if there is a value in the array with that field name, that value is returned.
	 * @retval mixed Returns an array if $field is NULL and there is an array to be returned, otherwise returns $array[$field], or NULL.
	 */
	public function fetch($field = NULL) {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			$data = $this->RESULT->fetch_assoc();
			if ( true === is_null($data) ) {
				$this->free();
			} else {
				reset($data);
			}
			if ( false === empty($field) ) {
				if ( true === asfw_exists($field, $data) ) {
					$data = $data[$field];
				}
			}
			return $data;
		}
		return NULL;
	}
	
	/**
	 * Fetches a Value Object from the result.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns a Value Object representation of the associative array.
	 */
	public function fetchVo() {
		$vo = $this->fetch();
		if ( true === is_array($vo) ) {
			$vo = new Artisan_Vo($vo);
		}
		return $vo;
	}

	/**
	 * Returns all data, with each row as an array, from a query. Be warned that
	 * this can return a lot of data if the query returns a lot.
	 * @author vmc <vmc@leftnode.com>
	 * @param $key_on_primary If true, all keys of the returned array will have the value of the primary key, otherwise
	 * they will be the index of the array.
	 * @retval array A 2D array of all fields matching the result query.
	 * @todo Finish implemented $key_on_primary.
	 */
	public function fetchAll($key_on_primary = false) {
		$result_data = array();
		while ( $row = $this->fetch() ) {
			$result_data[] = $row;
		}
		return $result_data;
	}

	/**
	 * Returns all data as a 2D array with each array value being a Value Object representation
	 * of the associative array row.
	 * @author vmc <vmc@leftnode.com>
	 * @retval array A 2D array of all fields matching the result query.
	 */
	public function fetchAllVo() {
		$result_data = array();
		while ( $row = $this->fetch() ) {
			$result_data[] = new Artisan_Vo($row);
		}
		return $result_data;
	}

	public function free() {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			$this->RESULT->free();
		}
		return true;
	}

	public function numRows() {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			return $this->RESULT->num_rows;
		}
		return 0;
	}
}