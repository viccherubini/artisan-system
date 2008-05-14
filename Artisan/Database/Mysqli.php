<?php


class Artisan_Database_Mysqli extends Artisan_Database {

	public function __construct($config = array()) {
		echo 'In mysqli constructor<br />';

		if ( count($config) > 0 ) {
			echo 'using database: ' . $config['db_name'] . '<br />';
		}
	}

	public function __destruct() {
		echo 'Mysqli dying<br />';
	}

	public function connect() {
		echo 'Mysqli connecting<br />';
	}

	public function disconnect() {
		echo 'Mysqli disconnecting<br />';
	}

	public function query($sql) {
		echo 'Mysqli querying: <strong>' . $sql . '</strong><br />';
	}
}

?>
