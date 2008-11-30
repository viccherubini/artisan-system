<?php


abstract class Artisan_Db_Adapter {
	///< The connection to the database.
	private $CONN = NULL;
	
	///< The configuration data.
	private $CONFIG = NULL;
	
	///< Whether or not the instance of this class is currently connected to the database.
	private $_is_connected = false;

	///< Whether or not a transaction has been started.
	private $_transaction_started = false;

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
	
	public function isConnected() {
		return $this->_is_connected;
	}
	
	abstract public function connect();
	abstract public function disconnect();
	abstract public function close();
	abstract public function query($sql);
	abstract public function update($table, $value_list, $where_sql = NULL);
	abstract public function insert($table, $value_list);
	abstract public function escape($value);
	
}