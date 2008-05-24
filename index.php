<?php

error_reporting(E_ALL);

require_once 'Artisan/Library.php';

Artisan_Library::load('Database');
Artisan_Library::load('Database/Mysqli');

$config = array(
	'server' => 'localhost',
	'username' => 'vic',
	'password' => 'dba89da',
	'dbname' => 'test'
);
	
Artisan_Database_Monitor::set(new Artisan_Database_Mysqli($config));
$db = Artisan_Database_Monitor::get();
$db->connect();

echo '<hr />';
echo 'Finished processing<br />';
echo 'Memory: ' . round(( memory_get_peak_usage() / (1024*1024)), 4) . 'MB<br />';



class Test {
	private $vo;

	public function __construct(VO $a) {
		$this->vo = $a;
	}

	public function g() {
		echo $this->vo->v();
	}

}

class VO {
	private $value = NULL;

	public function __construct($v) {
		$this->value = $v;
	}

	public function v() { return $this->value; }
}
?>
