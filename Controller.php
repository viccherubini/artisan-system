<?php



/**
 * Handles the Model-View Controller design pattern. The Plugin architecture allows
 * one to easily push a class (Aritsan or not) into the controller so that children
 * classes can easily take advantage of them.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Controller {
	protected $P = NULL;
	
	public function __construct() {
		$this->P = &Artisan_Controller_Plugin::get();
	}

	public function __destruct() {
	
	}
}

?>
