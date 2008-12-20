<?php

abstract class Artisan_Db_Result {
	abstract public function fetch($field = NULL);
	abstract public function fetchAll($key_on_primary = false);
	abstract public function free();
	abstract public function numRows();
}
