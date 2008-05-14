<?php

Artisan_Library::load('Database/Exception') or die('exception');

class Artisan_Database_Mysql extends Artisan_Database {

	public function __construct($config = array()) {
		echo 'In mysql constructor<br />';

		if ( count($config) > 0 ) {
			echo 'using database: ' . $config['db_name'] . '<br />';
		}
	}

	public function __destruct() {
		echo 'Mysql dying<br />';
	}

	public function connect() {
		echo 'Mysql connecting<br />';
		//throw new Artisan_Database_Exception(1, 'Some error occurred in connecting', __CLASS__, __FUNCTION__);
	}

	public function disconnect() {
		echo 'Mysql disconnecting<br />';
	}

	public function query(Artisan_Sql $sql) {
		echo 'Mysql querying<br />';
	}
}

?>
