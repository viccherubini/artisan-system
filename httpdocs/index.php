<?php

/**
 * Artisan System Framework SDK 0.3beta
 * This file is the entry point for the site.
 */
require_once 'configure.php';

$db = new Artisan_Db_Adapter_Mysqli($config_db);
$db->connect();

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

$db->disconnect();

exit;
