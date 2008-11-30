<?php

/**
 * @see Artisan_Db_Adapter
 */
require_once 'Artisan/Db/Adapter.php';

/**
 * @see Artisan_Db_Exception
 */
require_once 'Artisan/Db/Exception.php';

/**
 * This adapter class handles a connection to a Mysql database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Db_Adapter_Mysqli extends Artisan_Db_Adapter {

	public function __construct(Artisan_Config &$CFG) {
		echo 'In ' . __CLASS__ . '::' . __FUNCTION__ . '<br>';
	}
	
	public function __destruct() {
	
	}
	
	/**
	 * Returns the name of this class. This function can not be removed!
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the class name.
	 */
	public function name() {
		return __CLASS__;
	}

}