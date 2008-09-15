<?php

Artisan_Library::load('Server/Monitor');
Artisan_Library::load('Server/Exception');

abstract class Artisan_Server {
	protected $_server_address = NULL;
	protected $_server_port = 0;
	protected $_is_connected = false;
	

	public function __construct(Artisan_Config &$C) {
		$this->_unsetIsConnected();
		
		$this->_server_address = $C->server_address;
		$this->_server_port = $C->server_port;
		unset($C);
	}
	
	abstract public function connect();
	
	abstract public function close();
	
	abstract public function setHeader($name, $value);
	
	abstract public function setHeaderList($header_list);
	
	abstract public function send();
	
	abstract public function error();
	
		
	public function isConnected() {
		return $this->_is_connected;
	}
	
	protected function _setIsConnected() {
		$this->_is_connected = true;
	}
	
	protected function _unsetIsConnected() {
		$this->_is_connected = false;
	}
}

?>
