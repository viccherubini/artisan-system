<?php

/**
 * @see Artisan_Exception
 */
require_once 'Artisan/Exception.php';

/**
 * Controller Exception Class.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Controller_Exception extends Artisan_Exception {
	public function __construct($exp_string) {
		parent::__construct(ARTISAN_WARNING, $exp_string);
	}
}