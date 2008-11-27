<?php

require_once 'Db/Adapter.php';

class Artisan_Db_Mysqli extends Artisan_Db_Adapter {

	public function __construct(Artisan_Config &$CFG) {
	
	}
	
	public function __destruct() {
	
	}
	
	
	public function name() {
		return __CLASS__;
	}

}