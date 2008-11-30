<?php

interface Artisan_Db_Result_Interface {
	public function free();
	public function fetch($field = NULL);
	public function fetchAll();
}