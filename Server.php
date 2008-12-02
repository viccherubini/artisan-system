<?php

/**
 * Abstract class to connect to another server.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Server {
	///< The address of the server to connect.
	protected $_server_address = NULL;
	
	///< The port of the server to connect.
	protected $_server_port = 0;
	
	///< Whether or not there is a current connection.
	protected $_is_connected = false;
	
	/**
	 * Default constructor to build a new server connection object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C The configuration object.
	 * @retval Object Returns new Artisan_Server object.
	 */
	public function __construct(Artisan_Config &$C) {
		$this->_unsetIsConnected();
		
		$this->_server_address = $C->server_address;
		$this->_server_port = $C->server_port;
	}
	
	/**
	 * Whether or not there is a current connection.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns whether or not there is a current connection.
	 */
	public function isConnected() {
		return $this->_is_connected;
	}
	
	/**
	 * Sets that the server is currently connected.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	protected function _setIsConnected() {
		$this->_is_connected = true;
		return true;
	}
	
	/**
	 * Unsets that the server is currently connected.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	protected function _unsetIsConnected() {
		$this->_is_connected = false;
		return true;
	}
	
	/**
	 * Abstract method to connect to a server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on successful connection, false otherwise.
	 */
	abstract public function connect();
	
	/**
	 * Abstract method to disconnect from a server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on successful disconnection, false otherwise.
	 */
	abstract public function close();
	
	/**
	 * Abstract method to send a header to the server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	abstract public function setHeader($name, $value);
	
	/**
	 * Abstract method to send a list of headers to the server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	abstract public function setHeaderList($header_list);
	
	/**
	 * Abstract method to send data to the server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on successful send, false otherwise.
	 */
	abstract public function send();
	
	/**
	 * Abstract method to get an error from the server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval mixed Returns the error string if available, false otherwise.
	 */
	abstract public function error();
}
