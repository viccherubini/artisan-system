<?php

/**
 * @see Artisan_Validate
 */
require_once 'Artisan/Validate.php';

/**
 * Static class that contains methods to validate Ipv4 addresses.
  * @author vmc <vmc@leftnode.com>
 */
class Artisan_Validate_Ipv4 extends Artisan_Validate {
	private $_ip = NULL;

	public function __construct($ip = NULL) {
		$this->_ip = trim($ip);
	}
	
	/**
	 * Determines if a value is a valid IPv4 address.
	 * @author vmc <vmc@leftnode.com>
	 * @param $ip The IP address to test.
	 * @retval boolean Returns true if $ip is a valid IPv4 address, false otherwise.
	 * @todo Finish implementing this method.
	 */
	public function isValid($ip = NULL) {
		if ( true === empty($ip) ) {
			$ip = $this->_ip;
		}
		
		if ( true === empty($ip) ) {
			return false;
		}
	}
}