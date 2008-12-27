<?php

/**
 * Static class that contains methods to validate data.
 * @author vmc <vmc@leftnode.com>
 * @todo Finish writing this class!
 */
class Artisan_Validate {

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
	
	public static function isUri($u) {
		/**
		 * $matches will look like:
		 * $matches => Array(
		 *  [0] => $u,
		 *  [1] => http:// (also supports svn+ssh:// or other protocols)
		 *  [2] => username:password@
		 *  [3] => (www.|subdomain.)example.com -- If there is a subdomain or www., it is included here
		 *  [4] => :443
		 *  [5] => /index.php?query_string#anchor
		 * );
		 * I'm kinda proud of that RegEx :)
		 */
		$matches = array();
		$match_uri = preg_match('#^([a-z\-\+]+://)?([a-z0-9]+:[a-z0-9]+\@){0,1}([a-z0-9.]*[a-z0-9-]+\.[a-z]+){1}(:[0-9]{1,5}){0,1}(.*)#i', $u, $matches);
		
		echo $match_uri;
		asfw_print_r($matches);
	}
	
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