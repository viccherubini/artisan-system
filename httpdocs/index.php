<?php

/**
 * Artisan System Framework SDK 0.3
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

$result_agg = $db->select()
	->from('agg', 'a', 'price', 'product')
	->query()
	->aggregate('Avg', 'price', 'avg_price')
	->aggregate('Avg', 'price')
	->aggregate('Sum', 'price')
	->aggregate('Count', 'price');
asfw_print_r($result_agg->_aggResultList);

$db->disconnect();

exit;