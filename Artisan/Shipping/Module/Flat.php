<?php

require_once 'Artisan/Shipping/Exception.php';
require_once 'Artisan/Shipping/Module.php';
require_once 'Artisan/Shipping/Interface.php';

class Artisan_Shipping_Module_Flat extends Artisan_Shipping_Module implements Artisan_Shipping_Module_Interface {
	private $_id = 'frs';
	private $_name = 'Flat Rate Shipping';
	private $_rateList = array();
	
	public function __construct(Artisan_Config &$C) {
		if ( true === $C->key('rate_list') ) {
			$this->setRateList($C->rate_list);
		}
	}
	
	public function name() {
		return $this->_name;
	}
	
	public function id() {
		return $this->_id;
	}

	public function setRateList($rateList) {
		if ( count($rateList) < 1 ) {
			return false;
		}
		
		foreach ( $rateList as $name => $rate ) {
			$rate = floatval($rate);
			if ( $rate > 0 ) {
				$this->_rateList[$name] = $rate;
			}
		}
		return true;
	}
	
	public function quote() {
		// No need for the address, this is just a flat value(s).
		if ( count($this->_rateList) < 1 ) {
			throw new Artisan_Shipping_Exception(ARTISAN_WARNING, 'The rate list is empty, no rates can be set.');
		}
		$quote = array($this->_id => $this->_rateList);
		return $quote;
	}
}