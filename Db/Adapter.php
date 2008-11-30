<?php


abstract class Artisan_Db_Adapter {
	///< The connection to the database.
	protected $CONN = NULL;
	
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
	
	public function update($table, $value_list, $where_list = NULL) {
		$table = trim($table);
		
		if ( true === empty($table) ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'The table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		if ( false === is_array($value_list) || count($value_list) < 1 ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'The value list is not an array or is empty.', __CLASS__, __FUNCTION__);
		}
		
		$value_list = $this->_sanitizeValueList($value_list);
		
		$sql = "UPDATE `" . $table . "` SET ";
		$fl_len = count($value_list)-1;
		$i=0;
		foreach ( $value_list as $field => $value ) {
			$sql .= "`" . $field . "` = " . $value;
			if ( $i != $fl_len ) {
				$sql .= ', ';
			}
			$i++;
		}
		
		if ( false === empty($where_list) ) {
			if ( false === is_array($where_list) ) {
				$where_list = array($where_list);
			}
			
			$sql .= " WHERE (" . implode(") AND (", $where_list) . ")";
		}
		
		try {
			$this->query($sql);
		} catch ( Artisan_Db_Exception $e ) {
			throw $e;
		}
	}
	
	public function insert($table, $value_list) {
		$table = trim($table);
		
		if ( true === empty($table) ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'The table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		if ( false === is_array($value_list) || count($value_list) < 1 ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'The value list is not an array or is empty.', __CLASS__, __FUNCTION__);
		}
		
		$value_list = $this->_sanitizeValueList($value_list);
		
		$sql = "INSERT INTO `" . $table . "` ";
		$sql .= " VALUES(" . implode(", ", $value_list) . ")";
		
		try {
			$this->query($sql);
		} catch ( Artisan_Db_Exception $e ) {
			throw $e;
		}
	}
	
	private function _sanitizeValueList($vl) {
		foreach ( $vl as $i => $value ) {
			$vl[$i] = $this->escape($value);
			switch ( strtoupper($value) ) {
				case NULL: {
					$vl[$i] = 'NULL';
					break;
				}
				
				case 'NULL': 
				case 'NOW()': {
					$vl[$i] = $value;
					break;
				}
			
				default: {
					$vl[$i] = "'" . $value . "'";
					break;
				}
			}
		}
		
		return $vl;
	}
	
	abstract public function connect();
	abstract public function disconnect();
	abstract public function close();
	abstract public function query($sql);
	abstract public function escape($value);
}