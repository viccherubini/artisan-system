<?php

// Set's the include path to be up a directory so that Artisan/ can be loaded in.
// This can be updated to be: set_include_path('.:/path/to/artisan/'); if you wish
// to hardcode the path.
set_include_path('.:..');

require_once 'Artisan/Config/Array.php';
require_once 'Artisan/Db/Adapter/Mysqli.php';
require_once 'Artisan/Controller.php';

$config_db = new Artisan_Config_Array(array(
	'server' => '',
	'username' => '',
	'password' => '',
	'database' => ''
	)
);

// Create a new configuration instance for managing the controller. The
// three keys provided are required.
$config_controller = new Artisan_Config_Array(array(
	'directory' => 'public',
	'default_controller' => 'Admin',
	'default_method' => 'index'
	)
);
