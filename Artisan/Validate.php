<?php

/**
 * Static class that contains methods to validate data.
 * @author vmc <vmc@leftnode.com>
 * @todo Finish writing this class!
 */
class Artisan_Validate {
	/**
	 * Test if a value is composed entirely of ASCII values.
	 * @author vmc <vmc@leftnode.com>
	 * @param $val The value to test.
	 * @retval boolean True if $val is all ASCII text, false otherwise.
	 */
	public static function isAscii($val) {
		$clamp_low = ord(' ');
		$clamp_high = ord('~');
	
		$len = strlen($val);
		$is_ascii = true;
		for ( $i=0; $i<$len; $i++ ) {
			if ( ord($val[$i]) < $clamp_low || ord($val[$i]) > $clamp_high ) {
				$is_ascii = false;
			}
		}
		return $is_ascii;
	}
	
	/**
	 * Test if a value is a valid URI or not.
	 * @author vmc <vmc@leftnode.com>
	 * @param $u The URI to test.
	 * @retval boolean Returns true if value is an array, false otherwise.
	 * @todo Finish implementing this method.
	 */
	public static function isUri($u) {
		/**
		 * $matches will look like:
		 * @code
		 * $matches => Array(
		 *  [0] => $u,
		 *  [1] => http:// (also supports svn+ssh:// or other protocols)
		 *  [2] => username:password@
		 *  [3] => (www.|subdomain.)example.com -- If there is a subdomain or www., it is included here
		 *  [4] => :443
		 *  [5] => /index.php?query_string#anchor
		 * );
		 * @endcode
		 * I'm kinda proud of that RegEx :)
		 */
		$matches = array();
		$match_uri = preg_match('#^([a-z\-\+]+://)?([a-z0-9]+:[a-z0-9]+\@){0,1}([a-z0-9.]*[a-z0-9-]+\.[a-z]+){1}(:[0-9]{1,5}){0,1}(.*)#i', $u, $matches);
		
		echo $match_uri;
		asfw_print_r($matches);
	}
	
	/**
	 * Determines if a value is a valid IPv4 address.
	 * @author vmc <vmc@leftnode.com>
	 * @param $ip The IP address to test.
	 * @retval boolean Returns true if $ip is a valid IPv4 address, false otherwise.
	 * @todo Finish implementing this method.
	 */
	public static function isIpv4($ip) {
		
	}
	
	/**
	 * Validates whether an email is valid or not. Does not fully meet requirements set forth by 
	 * RFC 822 and RFC 2822. We're intentionally strict, however this lets a good amount through.
	 * @author vmc <vmc@leftnode.com>
	 * @param $s The email address to test.
	 * @retval boolean True if the value is an email address, false otherwise.
	 */
	public static function isEmail($s) {
		if ( 1 === preg_match('/([a-z0-9-_.!#$%^&*~`]*)(@[a-z0-9-]*\.[a-z]+)/i', $s) ) {
			return true;
		}
		return false;
	}
}