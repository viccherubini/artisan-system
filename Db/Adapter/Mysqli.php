<?php

require_once 'Db/Adapter.php';

class Artisan_Db_Adapter_Mysqli extends Artisan_Db_Adapter {

	public function __construct(Artisan_Config &$CFG) {
		echo 'In ' . __CLASS__ . '::' . __FUNCTION__ . '<br>';
	}
	
	public function __destruct() {
	
	}
	
	
	public function name() {
		return __CLASS__;
	}

}