<?php

/**
 * @see Artisan_Db_Adapter
 */
require_once 'Artisan/Db/Adapter.php';

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
 * This adapter class handles a connection to a Mysql database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Db_Adapter_Mysqli extends Artisan_Db_Adapter {

	public function __construct(Artisan_Config &$CFG) {
		$this->CONFIG = $CFG;
		$this->CONN = NULL;
	}
	
	public function __destruct() {
	
	}
	
	/**
	 * Returns the name of this class. This function can not be removed!
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the class name.
	 */
	public function name() {
		return __CLASS__;
	}




	public function connect() {
		$server = $this->CONFIG->server;
		$username = $this->CONFIG->username;
		$password = $this->CONFIG->password;
		$database = $this->CONFIG->database;
		
		$port = 3306;
		if ( true === asfw_exists('port', $this->CONFIG) ) {
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
			throw new Artisan_Db_Exception(ARTISAN_WARNING, mysqli_connect_error(), __CLASS__, __FUNCTION__);
		}

		$this->select = new Artisan_Db_Sql_Select($this->CONN);

		$this->_is_connected = true;
		return $this->CONN;
	}

	public function disconnect() {
		if ( true === $this->_is_connected ) {
			$this->CONN->close();
			$this->CONN = NULL;
			$this->_is_connected = false;
		}

		return true;
	}
	
	public function close() {
		$this->disconnect();
	}
	
	
	public function query($sql) {
		$sql = trim($sql);
		
		if ( true === empty($sql) ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'The SQL query is empty.', __CLASS__, __FUNCTION__);
		}
		
		$result = $this->CONN->query($sql);
		if ( true === is_object($result) && true === $result instanceof mysqli_result ) {
			return new Artisan_Db_Result_Mysqli($result);
		}
		
		return $result;
	}
	
	public function update($table, $value_list, $where_sql = NULL) {
	
	}
	
	public function insert($table, $value_list) {
	
	}
	
	public function escape($value) {
		return $this->CONN->real_escape_string($value);
	}
}