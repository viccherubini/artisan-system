<?php

/**
 * Creates a new Value Object dynamically. Just pass it an array and it'll internalize
 * it with each member of the array becoming an element of the class.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_VO {

	/**
	 * Default constructor, builds the object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $array A hash key/value array to internalize.
	 * @retval Object Returns a new Artisan_VO object.
	 */
	public function __construct($array) {
		if ( true === is_array($array) && count($array) > 0 ) {
			$this->_init($array);
		}
	}
	
	/**
	 * Destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	public function __destruct() { }

	/**
	 * This method takes an array, taking each key of that array and making that
	 * an element of this class dynamically. It runs recursively if necessary.
	 * @author vmc <vmc@leftnode.com>
	 * @param $root The start of the array to build.
	 * @retval NULL Returns nothing.
	 */
	protected function _init($root) {
		foreach ( $root as $k => $v ) {
			if ( true === is_array($v) ) {
				$this->$k = new Artisan_VO($v);
			} else {
				$this->$k = $v;
			}
		}
	}
	
	/**
	 * Overloaded magic method to ensure a value exists before getting it.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Mixed Returns the value if set, NULL otherwise.
	 */
	public function __get($e) {
		if ( false === isset($this->$e) ) {
			return NULL;
		}
	}
	
	/**
	 * Overloaded magic method to print out the value object.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string The textual representation of this object.
	 */
	public function __toString() {
		return asfw_print_r($this, true);
	}
}