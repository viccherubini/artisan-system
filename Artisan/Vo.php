<?php

/**
 * Creates a new Value Object dynamically. Just pass it an array and it'll internalize
 * it with each member of the array becoming an element of the class.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Vo {
	/**
	 * Default constructor, builds the object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $array A hash key/value array to internalize.
	 * @retval Object Returns a new Artisan_Vo object.
	 */
	public function __construct($array = array()) {
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
	 * @code
	 * $arr = array(
	 *     'a' => 'value',
	 *     'b' => 'value2',
	 *     'c' => array( 'd' => 'value3' )
	 * );
	 * @endcode
	 * To:
	 * $object->a has the value 'value', and
	 * $object->c->d has the value 'value3' in it.
	 * This way, one can easily internalize an array to access each element easily.
	 * @param $root The start of the array to build.
	 * @retval NULL Returns nothing.
	 */
	protected function _init($root) {
		foreach ( $root as $k => $v ) {
			if ( true === is_array($v) ) {
				$this->$k = new Artisan_Vo($v);
			} else {
				$this->$k = $v;
			}
		}
	}
	
	/**
	 * Overloaded magic method to ensure a value exists before getting it.
	 * @author vmc <vmc@leftnode.com>
	 * @param $e The element of the object to return.
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
	
	public function __unset($name) {
		if ( true === $this->key($name) ) {
			unset($this->$name);
		}
	}
	
	/**
	 * Determines if one or more elements exists in this value object. This method
	 * can be used by passing a variable number of arguments, or a single array:
	 * @code
	 * $exists = $VO->exists('elem1', 'elem2', 'elem3');
	 * $exists = $VO->exists(array('elem1', 'elem2', 'elem3'));
	 * @endcode
	 * In order for $exists to be true in the example above, all of the values must
	 * exist and NOT be NULL.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if all of the elements exist in the object and are not null, false otherwise.
	 */
	public function exists() {
		$argv = func_get_args();
		$argc = func_num_args();
		if ( 1 == $argc && true === is_array($argv[0]) ) {
			$argv = current($argv);
		}
		
		$found = true;
		foreach ( $argv as $e ) {
			if ( false === property_exists($this, $e) ) {
				$found = false;
			}
		}
		return $found;
	}
	
	/**
	 * Returns the number of keys in this value object.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int The number of keys.
	 */
	public function length() {
		return count(get_object_vars($this));
	}
	
	/**
	 * Determines of a specific key exists in the value object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $k The key to search for.
	 * @retval boolean True if the key exists, false otherwise.
	 */
	public function key($k) {
		return property_exists($this, $k);
	}
	
	/**
	 * Converts a Value Object back to the initial array that it was built from.
	 * @author vmc <vmc@leftnode.com>
	 * @retval array The Value Object array.
	 */
	public function toArray() {
		$vo_a = $this->_unwind($this);
		return $vo_a;
	}
	
	/**
	 * Unwinds the Value Object back to an array.
	 * @author vmc <vmc@leftnode.com>
	 * @param $root The root of the object to start at.
	 * @retval array The unwound Value Object array.
	 */
	private function _unwind($root) {
		$x = array();
		if ( true === is_object($root) ) {
			foreach ( $root as $key => $value ) {
				$x[$key] = $this->_unwind($value);
			}
		} else {
			$x = strval($root);
		}
		
		return $x;
	}
}

