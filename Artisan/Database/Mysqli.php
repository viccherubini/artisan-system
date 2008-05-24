<?php


class Artisan_Database_Mysqli extends Artisan_Database {
	private $_config = array();
	
	private $_db_conn = NULL;
	
	private $_db_result = NULL;
	
	public function __construct($config = array()) {
		if ( true === empty($config) ) {
			$this->_config = parent::$config;
		} else {
			if ( true === is_array($config) && count($config) > 0 ) {
				$this->_config = $config;
			}
		}
	}

	public function connect() {
		// Although generally against supressing errors, the @ is to supress a misconnection error
		// to allow the framework to handle it gracefully
		$server = $this->_config['server'];
		$username = $this->_config['username'];
		$password = $this->_config['password'];
		$dbname = $this->_config['dbname'];
		
		$port = 3306;
		if ( true === array_key_exists('port', $this->_config) ) {
			if ( intval($this->_config['port']) > 0 ) {
				$port = intval($this->_config['port']);
			}
		}
		
		$this->_db_conn = @new mysqli($server, $username, $_password, $dbname, $port);

		if ( 0 != mysqli_connect_errno() || false === $this->_db_conn ) {
			$this->_is_connected = false;
			return false;
		}
		
		$this->_is_connected = true;
		return $this->_db_conn;
	}

	public function disconnect() {
		if ( true === $this->_is_connected ) {
			$this->_db_conn->close();
			$this->_db_conn = NULL;
			$this->_is_connected = false;
		}
	}
	
	public function rowCount() { }
	public function rowsAffected() { }
	
	public function query(Artisan_Sql $sql) { }
	
	public function fetch() { }
	public function free() { }
	
	public function isConnected() {
		return $this->_is_connected;
	}
	
	public function escape($string) {
		return $this->_db_conn->real_escape_string($string);
	}
	
	private function _start() { }
	private function _cancel() { }
	private function _end() { }
	
	public function queue($query_list) { }
}

?>
