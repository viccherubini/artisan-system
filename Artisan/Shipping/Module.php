<?php

/**
 * All of the actual shipping modules originate from this class. The Artisan_Shipping_Module
 * class is the base class for all modules so that way different modules can have different
 * data associated with them. For example, the USPS module could have one originating address
 * while the UPS module could have a differnt one. Additionally, it allows different configuration
 * to be sent to each module.
 * All each module returns data in the format:
 * @code
 * array(
 *   id => array(
 *     'METHOD1' => 4.56,
 *     'METHOD2' => 15.93,
 *     'METHOD3' => 21.45
 *   )
 * );
 * @endcode
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Shipping_Module {
	protected $_config = NULL;
	protected $_originAddress = NULL;
	protected $_destAddress = NULL;
	protected $_weight = 0.0;
	
	public function __construct(Artisan_Address $originAddr, Artisan_Address $destAddr = NULL) {
		$this->setOriginAddress($originAddr);
		if ( false === is_null($destAddr) ) {
			$this->setOriginAddress($destAddr);
		}
		$this->_config = $C;
	}
	
	public function setOriginAddress(Artisan_Address $addr) {
		$this->_originAddress = $addr;
		return true;
	}
	
	public function setDestAddress(Artisan_Address $addr) {
		$this->_destAddress = $addr;
		return true;
	}
	
	public function setWeight($weight) {
		$weight = floatval($weight);
		if ( $weight > 0.0 ) {
			$this->_weight = $weight;
		}
		return true;
	}
}