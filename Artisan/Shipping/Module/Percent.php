<?php

require_once 'Artisan/Shipping/Exception.php';
require_once 'Artisan/Shipping/Module.php';
require_once 'Artisan/Shipping/Interface.php';

class Artisan_Shipping_Module_Percent extends Artisan_Shipping_Module implements Artisan_Shipping_Module_Interface {
	private $_id = 'percent';
	private $_name = 'Percentage Of A Total';
	private $_price = 0.00;
	private $_percent = 0.00;
	
	public function __construct($price = 0.0, $percent = 0.00) {
		$this->setPrice($price);
		$this->setPercent($percent);
	}
	
	public function name() {
		return $this->_name;
	}
	
	public function id() {
		return $this->_id;
	}

	public function setPrice($price) {
		$price = floatval($price);
		if ( $price > 0 ) {
			$this->_price = $price;
		}
	}
	
	/**
	 * Sets the percentage of markup or down the shipping should be. This number is 
	 * assumed to already be in percent format. In other words, the value .85 should
	 * be used for 85%.
	 * @author vmc <vmc@leftnode.com>
	 */
	public function setPercent($percent) {
		$percent = floatval($percent);
		if ( $percent > 0 ) {
			$this->_percent = $percent;
		}
	}
	
	public function quote() {
		// No need for the address, this is just a flat value(s).
		$rate = $this->_price * $this->_percent;
		$rate = round($rate, 2);
		$quote = array(
			$this->_id => array(
				'PERCENT' => $rate
			)
		);
		return $quote;
	}
}