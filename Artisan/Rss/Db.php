<?php

require_once 'Artisan/Rss.php';

class Artisan_Rss_Db extends Artisan_Rss {
	protected $DB = NULL;
	
	public function __construct(Artisan_Db &$DB) {
		$this->DB = &$DB;
	}
	
	public function load() {
	
	}
}