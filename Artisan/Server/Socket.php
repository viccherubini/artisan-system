<?php

/**
 * @see Artisan_Server
 */
require_once 'Artisan/Server.php';

/**
 * @see Artisan_Server_Exception
 */
require_once 'Artisan/Server/Exception.php';

class Artisan_Server_Socket extends Artisan_Server {
	/**
	 * Default constructor for connecting to a server.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C The configuration object that contains the server address.
	 * @retval Object New Artisan_Server_Socket object.
	 */
	public function __construct(Artisan_Config &$C) {
		
	}

	/**
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Destroys the object.
	 */
	public function __destruct() {

	}

	/**
	 * Connects to the specified server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Exits the current script.
	 * @todo Finish implementing this!
	 */
	public function connect() {
		exit('In ' . __CLASS__ . '::' . __FUNCTION__);
	}

	/**
	 * Disconnects to the current server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Exits the current script.
	 * @todo Finish implementing this!
	 */
	public function close() {
		exit('In ' . __CLASS__ . '::' . __FUNCTION__);
	}

	/**
	 * Sets a header to send to the server.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the header to send.
	 * @param $value The value of the header to send.
	 * @retval NULL Exits the current script.
	 * @todo Finish implementing this!
	 */
	public function setHeader($name, $value) {
		exit('In ' . __CLASS__ . '::' . __FUNCTION__);
	}
	
	/**
	 * Sets a list of headers to send to the server.
	 * @author vmc <vmc@leftnode.com>
	 * @param $header_list The list of headers in key/value pair format.
	 * @retval NULL Exits the current script.
	 * @todo Finish implementing this!
	 */
	public function setHeaderList($header_list) {
		exit('In ' . __CLASS__ . '::' . __FUNCTION__);
	}
	
	/**
	 * Send the request to the server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Exits the current script.
	 * @todo Finish implementing this!
	 */
	public function send() {
		exit('In ' . __CLASS__ . '::' . __FUNCTION__);
	}
	
	/**
	 * Fetches the last error from the server.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Exits the current script.
	 * @todo Finish implementing this!
	 */
	public function error() {
		exit('In ' . __CLASS__ . '::' . __FUNCTION__);
	}
}