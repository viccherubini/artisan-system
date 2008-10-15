<?php

Artisan_Library::load('Server/Exception');

class Artisan_Server_Curl extends Artisan_Server {
	private $_server = NULL;
	
	

	public function __destruct() {
		if ( true === $this->isConnected() ) {
			$this->close();
		}
	}
	
	public function connect() {
		$server = trim($this->_server_address);
		if ( true === empty($server) ) {
			throw new Artisan_Server_Exception(ARTISAN_WARNING, 'No server was specified to connect to.', __CLASS__, __FUNCTION__);
		}
		
		$this->_server = curl_init($server);
		
		if ( false === $this->_server ) {
			throw new Artisan_Server_Exception(ARTISAN_WARNING, 'Failed to connect to specified server: ' . $server, __CLASS__, __FUNCTION__);
		}
		
		$this->_setIsConnected();
		return true;
	}
	
	public function close() {
		if ( true === $this->isConnected() ) {
			curl_close($this->_server);
			$this->_unsetIsConnected();
			return true;
		}
		
		return false;
	}
	
	public function setHeader($name, $value) {
		if ( true === $this->isConnected() ) {
			return curl_setopt($this->_server, $name, $value);
		}
		
		return false;
	}
	
	public function setHeaderList($header_list) {
		if ( true === $this->isConnected() ) {
			if ( true === is_array($header_list) ) {
				return curl_setopt_array($this->_server, $header_list);
			}
		}
			
		return false;
	}
	
	public function send() {
		if ( true === $this->isConnected() ) {
			$response = curl_exec($this->_server);
			return $response;
		}
	}
	
	public function error() {
		if ( true === $this->isConnected() ) {
			$error_no = curl_errno($this->_server);
			$error_str = curl_error($this->_server);
		
			if ( $error_no == 0 && true === empty($error_str) ) {
				return true;
			} else {
				return $error_str;
			}
		}
		
		return false;
	}
}
