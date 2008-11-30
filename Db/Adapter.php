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

	///< The instance of the Artisan_Db_Sql_Select_Mysqli class for executing queries.
	public $select = NULL;
	
	///< The instance of the Artisan_Db_Sql_Insert_Mysqli class for executing queries.
	public $insert = NULL;
	
	///< The instance of the Artisan_Db_Sql_Update_Mysqli class for executing queries.
	public $update = NULL;

	///< The instance of the Artisan_Db_Sql_Delete_Mysqli class for executing queries.
	public $delete = NULL;

	///< The instance of the Artisan_Db_Sql_Replace_Mysqli class for executing queries.
	public $replace = NULL;


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
	//abstract public function update($table, $value_list, $where_sql = NULL);
	//abstract public function insert($table, $value_list);
	abstract public function escape($value);
	
}