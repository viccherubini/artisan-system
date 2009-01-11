<?php

/**
 * Artisan System Framework SDK 0.2
 * This file is the entry point for the site.
 */

// Set's the include path to be up a directory so that Artisan/ can be loaded in.
// This can be updated to be: set_include_path('.:/path/to/artisan/'); if you wish
// to hardcode the path.
set_include_path('.:..');

require_once 'Artisan/Config/Array.php';
require_once 'Artisan/Functions/System.php';
require_once 'Artisan/Db/Adapter/Mysqli.php';
require_once 'Artisan/Customer/Adapter/Db.php';

$config_db = new Artisan_Config_Array(array(
	'server' => '192.168.2.101',
	'username' => 'asfw_blog',
	'password' => 'DSpuE2p6MjTZZrpJ',
	'database' => 'artisan_blog'
	)
);

$db = new Artisan_Db_Adapter_Mysqli($config_db);
try {
	$db->connect();
} catch ( Artisan_Db_Exception $e ) {
	exit($e);
}

$config_cust = new Artisan_Config_Array(array(
	'table_list' => array(
		'customer' => 'customer',
		'comment' => 'customer_comment_history',
		'history' => 'customer_history',
		'field' => 'customer_field',
		'field_type' => 'customer_field_type',
		'field_value' => 'customer_field_value',
	),
	'db_adapter' => $db
	)
);
try {
	//$C = new Artisan_Customer_Adapter_Db($config_cust);
	//$C->load(5);
	//$db->query("SELECT '*' FROM some query");
	
} catch ( Artisan_Db_Exception $e ) {
	echo $e;
}

$db->disconnect();
/*
require_once 'Artisan/Controller.php';

// Create a new configuration instance for managing the controller. The
// three keys provided are required.
$config_controller = new Artisan_Config_Array(array(
	'directory' => 'Controllers',
	'default_controller' => 'Artisan',
	'default_method' => 'index'
	)
);

// The Artisan_Controller class is a singleton, so load it in as so.
$C = &Artisan_Controller::get();
$C->setConfig($config_controller);

try {
	echo $C->execute();
} catch ( Artisan_Controller_Exception $e ) {
	echo $e;
} catch ( Artisan_Exception $e ) {
	echo $e;
}

exit;
*/

echo '<br><br><hr>' . asfw_peak_memory();
