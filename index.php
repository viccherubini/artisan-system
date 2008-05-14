<?php

error_reporting(E_ALL);

require_once 'Artisan/Library.php';
Artisan_Library::load('Database');
Artisan_Library::load('Database/Mysqli');
Artisan_Library::load('Template');
Artisan_Library::load('Exception');

//test();

$t = Artisan_Template_Monitor::get();
try {
	$t->load('some_template');
} catch( Artisan_Template_Exception $e ) {
	echo $e->toString() . '<br />';
}

echo '<hr />';
echo 'Finished processing<br />';
echo 'Memory: ' . round(( memory_get_peak_usage() / (1024*1024)), 4) . 'MB<br />';

function test() {
	//Artisan_Database_Monitor::set(new Artisan_Database_Mysqli());
	$db = Artisan_Database_Monitor::get();

	try {
		$db->connect();
	} catch ( Artisan_Database_Exception $e ) {
		echo $e->toString() . '<br />';
	}

	$db->query('select * from table were table_id = 54');
	$db->disconnect();
}
?>
