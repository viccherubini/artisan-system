<?php


class Artisan_Database_Mysqli extends Artisan_Database {
	//private $CONFIG = array();

	private $CONN = NULL;

	private $RESULT = NULL;

	private $_query_list = NULL;

	private $_sql_type = NULL;

	private $_is_connected = false;

	private $_transaction_started = false;

	public function __destruct() {
		if ( true === $this->_is_connected && true === is_object($this->CONN) ) {
			$this->disconnect();
		}
	}


	/**
	 * Connect to the database.
	 * @access public
	 * @returns New database connection
	 */
	public function connect() {
		$server = $this->CONFIG->server;
		$username = $this->CONFIG->username;
		$password = $this->CONFIG->password;
		$dbname = $this->CONFIG->dbname;

		$port = 3306;
		if ( true === @isset($this->CONFIG->port) ) {
			if ( intval($this->CONFIG->port) > 0 ) {
				$port = intval($this->CONFIG->port);
			}
		}

		// Although generally against supressing errors, the @ is
		// to supress a misconnection error
		// to allow the framework to handle it gracefully
		$this->CONN = @new mysqli($server, $username, $password, $dbname, $port);

		if ( 0 != mysqli_connect_errno() || false === $this->CONN ) {
			$this->_is_connected = false;
			throw new Artisan_Database_Exception(ARTISAN_WARNING, mysqli_connect_error(), __CLASS__, __FUNCTION__);
		}

		$this->_is_connected = true;
		return $this->CONN;
	}

	/**
	 * Disconnect from the database if already connected.
	 */
	public function disconnect() {
		if ( true === $this->_is_connected ) {
			$this->CONN->close();
			$this->CONN = NULL;
			$this->_is_connected = false;
		}
	}

	/**
	 * Return the number of rows after a SELECT query.
	 */
	public function getNumRows() {
		return $this->RESULT->num_rows;
	}

	/**
	 * Return the number of rows affected after a query
	 * that alters rows, such as an INSERT, UPDATE, or
	 * DELETE.
	 */
	public function getRowsAffected() {
		return $this->CONN->affected_rows;
	}

	/**
	 * Performs a query against the dadtabase.
	 */
	public function query($sql) {
		if ( Artisan_Sql instanceof $sql ) {

		} else {

		}

		$result = $this->CONN->query($sql->retrieve());

		if ( true === is_object($result) ) {
			$this->RESULT = $result;
		} else {
			throw new Artisan_Database_Exception(
				ARTISAN_WARNING, $this->CONN->error,
				__CLASS__, __FUNCTION__
			);
		}

		return $result;
	}

	/**
	 * Query the database directly with a sql statement
	 */
	/*
	public function query($sql) {
		$result = $this->CONN->query($sql);

		if ( true === is_object($result) ) {
			$this->RESULT = $result;
		} else {
			throw new Artisan_Database_Exception(
				ARTISAN_WARNING, $this->CONN->error,
				__CLASS__, __FUNCTION__
			);
		}

		return $result;
	}
	*/

	/**
	 * Fetch an array row from the database. If this is used in a loop,
	 * such as a while ( $data = $db->fetch() ), the last call will
	 * return a null value, and thus trigger the free() method below
	 * to be called, ensuring the memory is always freed.
	 */
	public function fetchRow() {
		$data = $this->RESULT->fetch_assoc();
		if ( true === is_null($data) ) {
			$this->free();
		}

		return $data;
	}

	/**
	 * Use this method if you're expecting only one row back from the database.
	 * This method queries the database and returns that row as an array
	 * if the query was successful.
	 */
	public function queryFetch(Artisan_Sql $sql, $field = NULL) {
		$row = array();

		$this->query($sql);
		$row = $this->fetch();

		// See if they passed an option field to returnhttp://musclemayhem.com/forums/showthread.php?t=55517
		if ( false === empty($field) && exists($field, $row) ) {
			return $row[$field];
		}

		return $row;
	}

	/**
	 * Free the memory from the last SQL statement (generally only
	 * from SELECT or EXPLAIN queries).
	 */
	public function free() {
		if ( true === is_object($this->RESULT) ) {
			$this->RESULT->free();
		}
	}

	/**
	 * Whether or not the class is currently connected to the database.
	 */
	public function isConnected() {
		return $this->_is_connected;
	}

	/**
	 * Escape a string with the correct character set.
	 */
	public function escape($string) {
		return $this->CONN->real_escape_string($string);
	}



	public function start() {
		if ( false === $this->_transaction_started ) {
			$this->CONN->autocommit(false);
			$this->_transaction_started = true;
		}
	}


	public function commit() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->commit();
		}
	}

	public function rollback() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->rollback();
		}
	}




	/**
	 * Start transtional queries.
	 */
	protected function _start() {
		// Turn autocommit off
		$this->CONN->autocommit(false);
		$this->_transaction_started = true;
	}

	/**
	 * Rolls back any failed transitional queries.
	 */
	protected function _cancel() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->rollback();
		}
	}

	/**
	 * Commit a series of SQL transactions.
	 */
	protected function _commit() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->commit();
		}
	}

	/**
	 * Support for transactional queries. $query_list should be an array
	 * of Artisan_Sql objects. If any of the queries fail, all of them fail,
	 * and the database is returned to its original state.
	 */
	public function queue($query_list) {
		$this->_start();

		if ( false === is_array($query_list) || count($query_list) < 1 ) {
			$this->_transaction_started = false;
			return false;
		}

		$error = false;
		$len = count($query_list);
		for ( $i=0; $i<$len; $i++ ) {
			if ( true === $query_list[$i] instanceof Artisan_Sql ) {
				$success = $this->query($query_list[$i]);

				if ( false === $success ) {
					$error = true;
					break;
				}
			}
		}

		if ( true === $error ) {
			$this->_cancel();
		} else {
			$this->_commit();
		}

		return $error;
	}
}

?>