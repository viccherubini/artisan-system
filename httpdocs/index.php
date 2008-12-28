<?php

set_include_path('.:..');

require_once 'Artisan/Config/Array.php';
require_once 'Artisan/Controller.php';

$config_controller = new Artisan_Config_Array(array(
	'directory' => 'Controllers',
	'default_controller' => 'Artisan',
	'default_method' => 'index'
	)
);


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
