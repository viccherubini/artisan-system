<?php

require_once 'Library.php';

class Artisan_Db {
	private $conn = NULL;
	private $config = NULL;
	private $connected = false;
	private $select = NULL;
	private $update = NULL;
	private $insert = NULL;
	private $delete = NULL;
	private $query_list = array();
	private $debug = false;
	
	public function __construct() {
		
	}

	public function __destruct() {
		unset($this->config, $this->select, $this->insert, $this->delete, $this->query_list);
	}

	public function setConfig($config) {
		$this->config = $config;
		return $this;
	}

	public function getConfig() {
		return $this->config;
	}

	public function getQueryList() {
		return $this->query_list;
	}
	
	public function isConnected() {
		return $this->connected;
	}
	
	public function connect() {
		$server = $this->config['server'];
		$username = $this->config['username'];
		$password = $this->config['password'];
		$database = $this->config['database'];

		$port = 3306;
		if ( true === @isset($this->config['port']) ) {
			if ( intval($this->config['port']) > 0 ) {
				$port = intval($this->config['port']);
			}
		}

		// Although generally against supressing errors, the @ is
		// to supress a misconnection error
		// to allow the framework to handle it gracefully
		$this->conn = @new mysqli($server, $username, $password, $database, $port);

		if ( 0 != mysqli_connect_errno() || false === $this->CONN ) {
			$this->_is_connected = false;
			throw new Artisan_Exception(mysqli_connect_error());
		}

		$this->connected = true;
		return $this->conn;
	}
	
	public function close() {
		return $this->disconnect();
	}
	
	public function disconnect() {
		if ( true === $this->connected ) {
			$this->conn->close();
			$this->conn = NULL;
			$this->connected = false;
		}
		return true;
	}

	public function query($sql) {
		$sql = trim($sql);
		
		if ( true === empty($sql) ) {
			throw new Artisan_Exception('The SQL statement is empty.');
		}
		
		if ( true === is_object($this->conn) ) {
			$result = $this->conn->query($sql);
			
			if ( true === $result instanceof mysqli_result ) {
				if ( true === $this->debug ) {
					$this->query_list['success'][] = $sql;
				}
				
				return new Artisan_Db_Result($result);
			}
		}
		
		if ( false === $result ) {
			if ( true === $this->debug ) {
				$this->_queryList['error'][] = $sql;
			}
			
			$error_string = $this->conn->error;
			throw new Artisan_Exception("Failed to execute query: {$sql}. MySQL said: {$error_string}");
		}
		
		return $result;
	}
	
	public function multiQuery($sql) {
		$sql = trim($sql);
		
		if ( true === empty($sql) ) {
			throw new Artisan_Exception('The SQL statement is empty.');
		}
		
		if ( true === is_object($this->conn) ) {
			$result = $this->conn->multi_query($sql);
			
			if ( true === $result ) {
				if ( true === $this->_debug ) {
					$this->_queryList['success'][] = $sql;
				}
				
				/* Discard other results. */
				do {
					$result = $this->conn->use_result();
					if ( false !== $result ) {
						$result->close();
					}
				} while ( $this->conn->next_result() );
				
				return true;
			} else {
				$error_string = $this->conn->error;
				throw new Artisan_Exception("Failed to execute query: {$sql}. MySQL said: {$error_string}");
			}
		}
		
		return false;
	}

	public function select() {
		if ( NULL === $this->select ) {
			$this->select = new Artisan_Sql_Select($this);
		}
		return $this->select;
	}
	
	public function insert() {
		if ( NULL === $this->insert ) {
			$this->insert = new Artisan_Sql_Insert($this);
		}
		$this->insert->setReplace(false);
		return $this->insert;
	}
	
	public function update() {
		if ( NULL === $this->update ) {
			$this->update = new Artisan_Sql_Update($this);
		}
		return $this->update;
	}

	public function delete() {
		if ( NULL === $this->delete ) {
			$this->delete = new Artisan_Sql_Delete($this);
		}
		return $this->delete;
	}

	public function replace() {
		if ( NULL == $this->insert ) {
			$this->insert = new Artisan_Sql_Insert($this);
		}
		$this->insert->setReplace(true);
		return $this->insert;
	}

	public function insertId() {
		if ( true === $this->conn instanceof mysqli ) {
			return mysqli_insert_id($this->conn);
		}
		return 0;
	}
	
	public function affectedRows() {
		if ( true === $this->conn instanceof mysqli ) {
			return $this->conn->affected_rows;
		}
		return 0;
	}
	
	public function escape($value) {
		if ( true === $this->conn instanceof mysqli ) {
			return $this->conn->real_escape_string($value);
		}
		return addslashes($value);
	}

}