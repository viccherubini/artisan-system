<?php

class Artisan_Sql_General_Mysqli extends Artisan_Sql_General {
	///< The connection object to the database, assumes the database is already connected.
	private $CONN = NULL;
	
	///< The result object after the query has been executed, ready for retrieval if needed.
	private $RESULT = NULL;
	
	/**
	 * Builds a new object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $CONN A database connection object of type mysqli. Assumes the database is already connected.
	 * @retval Object A new instance of this class.
	 */
	public function __construct($CONN) {
		if ( true === $CONN instanceof mysqli ) {
			$this->CONN = $CONN;
		}
	}
	
	/**
	 * Destroys this object.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing, destroys the object.
	 */
	public function __destruct() {
	}
	
	/**
	 * Executes the set query against the database.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Sql_Exception If the query is empty.
	 * @retval Object Returns an instance of itself for chaining.
	 */
	public function query() {
		if ( true === empty($this->_sql) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The SQL query is empty.', __CLASS__, __FUNCTION__);
		}

		$result = $this->CONN->query($this->_sql);
		
		if ( false === $result || ( true === is_object($result) && false === $result instanceof mysqli_result ) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, $this->CONN->error, __CLASS__, __FUNCTION__);
		}
		
		$this->RESULT = $result;
		
		return $this;
	}

	/**
	 * After the query has been successfully executed, this will return the result for further manipulation.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Sql_Exception If the query failed or wasn't executed and the RESULT object was not set.
	 * @retval Object Returns an instance of the result object.
	 */
	public function result() {
		if ( false === $this->RESULT instanceof mysqli_result ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 
				'The RESULT object is not of type mysqli_result, the query probably failed, 
				has not been executed, or was a data manipulation query (such as an INSERT, UPDATE, or DELETE).',
				__CLASS__, __FUNCTION__
			);
		}
		
		return $this->RESULT;
	}
}
