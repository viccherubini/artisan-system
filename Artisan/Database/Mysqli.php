<?php


class Artisan_Database_Mysqli extends Artisan_Database {
	private $_config = array();
	
	private $_db_conn = NULL;
	
	private $_db_result = NULL;
	
	private $_query_list = NULL;
	
	private $_sql_type = NULL;
	
	private $_is_connected = false;
	
	private $_transaction_started = false;
	
	/**
	 * Default constructor.
	 */
	public function __construct(Artisan_Config $config = NULL) {
		if ( true === empty($config) ) {
			$this->_config = parent::$config;
		} else {
			if ( $config instanceof Artisan_Config ) {
				$this->_config = $config;
			}
		}
	}

	public function __destruct() {
		if ( true === $this->_is_connected && true === is_object($this->_db_conn) ) {
			$this->disconnect();
		}
	}
	
	/**
	 * Connect to the database.
	 */
	public function connect() {
		$server = $this->_config->server;
		$username = $this->_config->username;
		$password = $this->_config->password;
		$dbname = $this->_config->dbname;
		
		$port = 3306;
		if ( true === @isset($this->_config->port) ) {
			if ( intval($this->_config->port) > 0 ) {
				$port = intval($this->_config->port);
			}
		}
		
		// Although generally against supressing errors, the @ is 
		// to supress a misconnection error
		// to allow the framework to handle it gracefully
		$this->_db_conn = @new mysqli($server, $username, $password, $dbname, $port);

		if ( 0 != mysqli_connect_errno() || false === $this->_db_conn ) {
			$this->_is_connected = false;
			
			throw new Artisan_Database_Exception(
				ARTISAN_WARNING, mysqli_connect_error(),
				__CLASS__, __FUNCTION__
			);
		}
		
		$this->_is_connected = true;
		return $this->_db_conn;
	}

	/**
	 * Disconnect from the database if already connected.
	 */
	public function disconnect() {
		if ( true === $this->_is_connected ) {
			$this->_db_conn->close();
			$this->_db_conn = NULL;
			$this->_is_connected = false;
		}
	}
	
	/**
	 * Return the number of rows after a SELECT query.
	 */
	public function rowCount() {
		return $this->_db_result->num_rows;	
	}
	
	/**
	 * Return the number of rows affected after a query
	 * that alters rows, such as an INSERT, UPDATE, or 
	 * DELETE.
	 */
	public function rowsAffected() {
		return $this->_db_conn->affected_rows;
	}
	
	/**
	 * Performs a query against the dadtabase. The query must be
	 * of type Artisan_Sql, enforcing programmers to use
	 * parameterized SQL.
	 */
	public function query(Artisan_Sql $sql) {
		$result = $this->_db_conn->query($sql->retrieve());
		
		if ( true === is_object($result) ) {
			$this->_db_result = $result;
		} else {
			throw new Artisan_Database_Exception(
				ARTISAN_WARNING, $this->_db_conn->error,
				__CLASS__, __FUNCTION__
			);
		}
		
		return $result;
	}
	
	/**
	 * Query the database directly with a sql statement
	 */
	public function querydb($sql) {
		$result = $this->_db_conn->query($sql);
		
		if ( true === is_object($result) ) {
			$this->_db_result = $result;
		} else {
			throw new Artisan_Database_Exception(
				ARTISAN_WARNING, $this->_db_conn->error,
				__CLASS__, __FUNCTION__
			);
		}
		
		return $result;
	}
	
	/**
	 * Fetch an array from the database. If this is used in a loop,
	 * such as a while ( $data = $db->fetch() ), the last call will
	 * return a null value, and thus trigger the free() method below
	 * to be called, ensuring the memory is always freed.
	 */
	public function fetch() {
		$data = $this->_db_result->fetch_assoc();
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
	public function queryFetch(Artisan_Sql $sql) {
		$row = array();
	
		$this->query($sql);
		$row = $this->fetch();
		
		return $row;
	}
	
	/**
	 * Free the memory from the last SQL statement (generally only
	 * from SELECT or EXPLAIN queries).
	 */
	public function free() {
		if ( true === is_object($this->_db_result) ) {
			$this->_db_result->free();
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
		return $this->_db_conn->real_escape_string($string);
	}
	
	/**
	 * Start transtional queries.
	 */
	protected function _start() {
		// Turn autocommit off
		$this->_db_conn->autocommit(false);
		$this->_transaction_started = true;		
	}
	
	/**
	 * Rolls back any failed transitional queries.
	 */
	protected function _cancel() {
		if ( true === $this->_transaction_started ) {
			$this->_db_conn->rollback();
		}
	}
	
	/**
	 * Commit a series of SQL transactions.
	 */
	protected function _commit() {
		if ( true === $this->_transaction_started ) {
			$this->_db_conn->commit();
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