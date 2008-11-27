<?php

interface Artisan_Db_Interface {
	public function connect();
	public function disconnect();
	public function close();
	public function isConnected();
	
	// Transaction support
	
	
	public function escape($value);

}