<?php

//asfw_print_r(get_declared_classes());

class Artisan_Sql_Delete_Mysqli extends Artisan_Sql_Delete {

	private $CONN = NULL;
	private $RESULT = NULL;

	public function __construct($CONN) {
		if ( true === $CONN instanceof mysqli ) {
			$this->CONN = $CONN;
		}
	}

	public function __destruct() { }

	public function build() {

	}

	public function query() {

	}

}
