<?php

/**
 * @see Artisan_Db
 */
require_once 'Artisan/Db.php';

/**
 * @see Artisan_Db_Exception
 */
require_once 'Artisan/Db/Exception.php';

/**
 * @see Artisan_Db_Result_Mysqli
 */
require_once 'Artisan/Db/Result/Mysqli.php';

/**
 * @see Artisan_Db_Sql_Select_Mysqli
 */
require_once 'Artisan/Db/Sql/Select/Mysqli.php';

/**
 * @see Artisan_Db_Sql_Insert_Mysql
 */
require_once 'Artisan/Db/Sql/Insert/Mysqli.php';

/**
 * @see Artisan_Db_Sql_Update_Mysqli
 */
require_once 'Artisan/Db/Sql/Update/Mysqli.php';

/**
 * @see Artisan_Db_Sql_Delete_Mysqli
 */
require_once 'Artisan/Db/Sql/Delete/Mysqli.php';


/**
 * The Mysqli class for connecting to and querying a mysql database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Db_Adapter_Mysqli extends Artisan_Db {
	/**
	 * Destructor, disconnects from the database if currently connected.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Destroys the object.
	 */
	public function __destruct() {
		if ( true === $this->_is_connected && true === is_object($this->CONN) ) {
			$this->disconnect();
			$this->_is_connected = false;
		}
		unset($this->CONFIG);
	}

	/**
	 * Connect to the database.
	 * @throw Artisan_Database_Exception Throws a new exception if the database connection can not be made.
	 * @retval Object New database connection
	 * @todo Update the isset() below for the port to uses CONFIG->exists().
	 */
	public function connect() {
		$server = $this->CONFIG->server;
		$username = $this->CONFIG->username;
		$password = $this->CONFIG->password;
		$database = $this->CONFIG->database;

		$port = 3306;
		if ( true === @isset($this->CONFIG->port) ) {
			if ( intval($this->CONFIG->port) > 0 ) {
				$port = intval($this->CONFIG->port);
			}
		}

		// Although generally against supressing errors, the @ is
		// to supress a misconnection error
		// to allow the framework to handle it gracefully
		$this->CONN = @new mysqli($server, $username, $password, $database, $port);

		if ( 0 != mysqli_connect_errno() || false === $this->CONN ) {
			$this->_is_connected = false;
			throw new Artisan_Db_Exception(mysqli_connect_error());
		}

		$this->_is_connected = true;
		return $this->CONN;
	}

	/**
	 * Disconnect from the database if already connected.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Always returns true.
	 */
	public function disconnect() {
		if ( true === $this->_is_connected ) {
			$this->CONN->close();
			$this->CONN = NULL;
			$this->_is_connected = false;
		}

		return true;
	}

	/**
	 * Executes a query against the database server.
	 * @author vmc <vmc@leftnode.com>
	 * @param $sql The query to execute.
	 * @throw Artisan_Db_Exception If the SQL statement is empty.
	 * @throw Artisan_Db_Exception If the query fails to execute as a result of a syntax or log error.
	 * @retval mixed Returns a result object if the query returns data, boolean true/false otherwise.
	 */
	public function query($sql) {
		$sql = trim($sql);
		
		if ( true === empty($sql) ) {
			throw new Artisan_Db_Exception('The SQL statement is empty.');
		}
		
		if ( true === is_object($this->CONN) ) {
			$result = $this->CONN->query($sql);
			
			if ( true === $result instanceof mysqli_result ) {
				if ( true === $this->_debug ) {
					$this->_queryList['success'][] = $sql;
				}
				
				return new Artisan_Db_Result_Mysqli($result);
			}
		}
		
		if ( false === $result ) {
			if ( true === $this->_debug ) {
				$this->_queryList['error'][] = $sql;
			}
			
			$error_string = $this->CONN->error;
			throw new Artisan_Db_Exception('Failed to execute query: "' . $sql . '", MySQL said: ' . $error_string);
		}
		return $result;
	}
	
	public function multiQuery($sql) {
		$sql = trim($sql);
		
		if ( true === empty($sql) ) {
			throw new Artisan_Db_Exception('The SQL statement is empty.');
		}
		
		if ( true === is_object($this->CONN) ) {
			$result = $this->CONN->multi_query($sql);
			
			if ( true === $result ) {
				if ( true === $this->_debug ) {
					$this->_queryList['success'][] = $sql;
				}
				
				/* Discard other results. */
				do {
					$result = $this->CONN->use_result();
					if ( false !== $result ) {
						$result->close();
					}
				} while ( $this->CONN->next_result() );
				
				return true;
			} else {
				$error_string = $this->CONN->error;
				throw new Artisan_Db_Exception('Failed to execute query: "' . $sql . '", MySQL said: ' . $error_string);
			}
		}
		
		return false;
	}
	
	/**
	 * Creates a new SELECT object to fetch data from the database. Uses Lazy Initialization.
	 * @author vmc <vmc@leftnode.com>
	 * @retval object Returns new Artisan_Db_Sql_Select_Mysqli object.
	 */
	public function select() {
		if ( NULL == $this->_select ) {
			$this->_select = new Artisan_Db_Sql_Select_Mysqli($this);
		}
		return $this->_select;
	}
	
	/**
	 * Creates a new INSERT object to add data to the database. Uses Lazy Initialization.
	 * @author vmc <vmc@leftnode.com>
	 * @retval object Returns new Artisan_Db_Sql_Insert_Mysqli object.
	 */
	public function insert() {
		if ( NULL == $this->_insert ) {
			$this->_insert = new Artisan_Db_Sql_Insert_Mysqli($this);
		}
		$this->_insert->setReplace(false);
		return $this->_insert;
	}
	
	/**
	 * Creates a new UPDATE object. Uses Lazy Initialization.
	 * @author vmc <vmc@leftnode.com>
	 * @retval object Returns new Artisan_Db_Sql_Update_Mysqli object.
	 */
	public function update() {
		if ( NULL == $this->_update ) {
			$this->_update = new Artisan_Db_Sql_Update_Mysqli($this);
		}
		return $this->_update;
	}
	
	/**
	 * Creates a new DELETE object. Uses Lazy Initialization.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Returns a new Artisan_Db_Sql_Delete_Mysqli object.
	 */
	public function delete() {
		if ( NULL == $this->_delete ) {
			$this->_delete = new Artisan_Db_Sql_Delete_Mysqli($this);
		}
		return $this->_delete;
	}
	
	/**
	 * Creates a new REPLACE object. Uses Lazy Initialization.
	 * @author vmc <vmc@leftnode.com>
	 * @retval object Returns new Artisan_Db_Sql_Insert_Mysqli object with the REPLACE parameter set.
	 */
	public function replace() {
		if ( NULL == $this->_insert ) {
			$this->_insert = new Artisan_Db_Sql_Insert_Mysqli($this);
		}
		$this->_insert->setReplace(true);
		return $this->_insert;
	}
	
	/**
	 * Starts a transaction if the database or table type supports transactions.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 * @todo Finish implementing this!
	 */
	public function start() {
		exit('start transaction');
	}
	
	/**
	 * Commits the transaction queries.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on success, false otherwise.
	 * @todo Finish implementing this!
	 */
	public function commit() {
		exit('commit transaction');
	}
	
	/**
	 * Rollbacks the transaction queries if any of them fail.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on success, false otherwise.
	 * @todo Finish implementing this!
	 */
	public function rollback() {
		exit('rollback transaction');
	}
	
	/**
	 * Returns the last INSERT ID from the last INSERT query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int The INSERT ID, 0 if it can't be found.
	 */
	public function insertId() {
		if ( true === $this->CONN instanceof mysqli ) {
			return mysqli_insert_id($this->CONN);
		}
		return 0;
	}
	
	/**
	 * Returns the number of affected rows from the last UPDATE/INSERT/REPLACE query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int The number of affected rows.
	 */
	public function affectedRows() {
		if ( true === $this->CONN instanceof mysqli ) {
			return $this->CONN->affected_rows;
		}
		return 0;
	}
	
	/**
	 * Escapes a value to make it safe for insertion into a database. Uses the real_escape_string()
	 * method if the database has a connection, otherwise uses addslashes().
	 * @author vmc <vmc@leftnode.com>
	 * @param $value The value to escape.
	 * @retval string The escaped value.
	 */
	public function escape($value) {
		if ( true === $this->CONN instanceof mysqli ) {
			return $this->CONN->real_escape_string($value);
		}
		return addslashes($value);
	}
}