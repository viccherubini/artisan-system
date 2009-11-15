<?php

/**
 * Static class that contains methods to validate a Uri.
 * @author vmc <vmc@leftnode.com>
 * @author rafshar <rafshar@gmail.com>
 */
class Artisan_Validate_Uri {
	private $_u = NULL;

	public function __construct($u = NULL) {
		$this->_u = trim($u);
	}
	
	/**
	 * Test if a value is a valid URI or not.
	 * @author vmc <vmc@leftnode.com>
	 * @author rafshar <rafshar@gmail.com>
	 * @param $u The URI to test.
	 * @retval boolean Returns true if value is an array, false otherwise.
	 * @todo Finish implementing this method.
	 */
	public function isValid($u = NULL) {
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
		if ( true === empty($u) ) {
			$u = $this->_u;
		}
		
		if ( true === empty($u) ) {
			return false;
		}
		
		$matches = array();
		$match_uri = preg_match('#^([a-z\-\+]+://)?([a-z0-9]+:[a-z0-9]+\@){0,1}([a-z0-9.]*[a-z0-9-]+\.[a-z]+){1}(:[0-9]{1,5}){0,1}(.*)#i', $u, $matches);
		
		echo $match_uri;
	}
}