<?php

require_once 'Artisan/Shipping/Exception.php';
require_once 'Artisan/Shipping/Module.php';
require_once 'Artisan/Shipping/Interface.php';

class Artisan_Shipping_Module_Free extends Artisan_Shipping_Module implements Artisan_Shipping_Module_Interface {
	private $_name = 'Free Shipping';
	private $_id = 'free';
	
	public function __construct() {
	}
	
	public function name() {
		return $this->_name;
	}
	
	public function id() {
		return $this->_id;
	}

	public function quote() {
		$rate = 0.0;
		$quote = array(
			$this->_id => array(
				'FREE' => $rate
			)
		);
		return $quote;
	}
}