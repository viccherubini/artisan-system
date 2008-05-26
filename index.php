<?php

error_reporting(E_ALL);

require_once 'Artisan/Library.php';

Artisan_Library::load('Exception');
/*
Artisan_Library::load('Database');
Artisan_Library::load('Database/Mysqli');

$config = array(
	'server' => 'localhost',
	'username' => 'vcherubini2',
	'password' => 'xt97Hsdba!',
	'dbname' => 'artisan'
);
	
Artisan_Database_Monitor::set(new Artisan_Database_Mysqli($config));
$db = Artisan_Database_Monitor::get();

try {
	$db->connect();
} catch ( Artisan_Database_Exception $e ) {
	echo $e;
}

$db->disconnect();
*/

Artisan_Library::load('Sql');
Artisan_Library::load('Sql/Select');

Artisan_Sql_Monitor::set(new Artisan_Sql_Select());
$select = Artisan_Sql_Monitor::get();



pprint_r(Artisan_Library::getObjectList());

echo '<hr />';
echo 'Finished processing<br />';
echo 'Memory: ' . round(( memory_get_peak_usage() / (1024*1024)), 4) . 'MB<br />';



function pprint_r($a) {
	print '<pre>'; print_r($a); print '</pre>';
}

?>