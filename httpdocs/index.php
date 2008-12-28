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
