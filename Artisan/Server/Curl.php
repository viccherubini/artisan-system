<?php

/**
 * @see Artisan_Server
 */
require_once 'Artisan/Server.php';

/**
 * @see Artisan_Server_Exception
 */
require_once 'Artisan/Server/Exception.php';

/**
 * This class uses cURL to connect to a remote server to send or retrieve data.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Server_Curl extends Artisan_Server {
	///< The server instance.
	private $_server = NULL;
	
	///< The address of the server.
	private $_server_address;
	
	///< Whether or not the server currently has a connection.
	private $_is_connected = false;
	
	/**
	 * Default constructor for connecting to a server.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C The configuration object that contains the server address.
	 * @retval Object New Artisan_Server_Curl object.
	 */
	public function __construct(Artisan_Config &$C) {
		$this->_server_address = $C->server_address;
	}
	 
	/**
	 * Destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Destroys the object.
	 */
	public function __destruct() {
		if ( true === $this->_is_connected ) {
			$this->close();
		}
	}
	
	/**
	 * Connects to the specified server.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Server_Exception If the server address was empty.
	 * @throw Artisan_Server_Exception If the connection to the server could not be made.
	 * @retval boolean Returns true on successful connection.
	 */
	public function connect() {
		$server = trim($this->_server_address);
		if ( true === empty($server) ) {
			throw new Artisan_Server_Exception(ARTISAN_WARNING, 'No server was specified to connect to.');
		}
		
		$this->_server = curl_init($server);
		
		if ( false === $this->_server ) {
			throw new Artisan_Server_Exception(ARTISAN_WARNING, 'Failed to connect to specified server: ' . $server);
		}
		
		$this->_is_connected = true;
		return true;
	}
	
	/**
	 * Closes the connection to the server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function close() {
		if ( true === $this->_is_connected ) {
			curl_close($this->_server);
			$this->_is_connected = false;
		}
		
		return true;
	}
	
	/**
	 * Sets a header to send to the server.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the header to send.
	 * @param $value The value of the header to send.
	 * @retval boolean Returns true if header sent, false otherwise.
	 */
	public function setHeader($name, $value) {
		if ( true === $this->_is_connected ) {
			return curl_setopt($this->_server, $name, $value);
		}
		
		return false;
	}
	
	/**
	 * Sets a list of headers to send to the server.
	 * @author vmc <vmc@leftnode.com>
	 * @param $header_list The list of headers in key/value pair format.
	 * @retval boolean Returns true if headers sent, false otherwise.
	 */
	public function setHeaderList($header_list) {
		if ( true === $this->_is_connected ) {
			if ( true === is_array($header_list) && count($header_list) > 0 ) {
				return curl_setopt_array($this->_server, $header_list);
			}
		}
			
		return false;
	}
	
	/**
	 * Send the request to the server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Mixed Returns the response from the server.
	 */
	public function send() {
		if ( true === $this->_is_connected ) {
			$response = curl_exec($this->_server);
			return $response;
		}
		
		return NULL;
	}
	
	/**
	 * Fetches the last error from the server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Mixed Returns false if no error, otherwise, returns the error string.
	 */
	public function error() {
		if ( true === $this->_is_connected() ) {
			$error_no = curl_errno($this->_server);
			$error_str = curl_error($this->_server);
		
			if ( $error_no == 0 && true === empty($error_str) ) {
				return false;
			} else {
				return $error_str;
			}
		}
		
		return false;
	}
}