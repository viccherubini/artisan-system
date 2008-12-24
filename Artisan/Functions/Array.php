<?php


/**
 * This method wraps &lt;pre&gt; tags around an array or object before printing
 * it out to display it nicely in a browser.
 * @author vmc <vmc@leftnode.com>
 * @param $array The array or object to display.
 * @param $return Optional parameter to return the value in a string or echo it directly. Default is to echo.
 * @retval string If $return is true, returns a string of formatted text, otherwise, returns true.
 */
function asfw_print_r($array, $return = false) {
	$str = '<pre>' . print_r($array, true) . '</pre>';
	if ( true === $return ) {
		return $str;
	} else {
		echo $str;
	}
	
	return true;
}

/**
 * Determines if a key exists in an array and isn't empty or a class has a
 * member variable and isn't empty.
 * @author vmc <vmc@leftnode.com>
 * @param $key The key or variable to check for existence.
 * @param $array The array or object to search.
 * @retval boolean True if the key or variable exists and isn't empty, false otherwise.
 */
function asfw_exists($key, $array) {
	if ( true === is_object($array) ) {
		if ( true === property_exists($array, $key) ) {
			if ( false === empty($array->$key) ) {
				return true;
			}
		}
	} elseif ( true === is_array($array) ) {
		if ( true === array_key_exists($key, $array) ) {
			if ( false === empty($array[$key]) ) {
				return true;
			}
		}
	}
	
	return false;
}

/**
 * If an element exists in an array or object, it returns it's value
 * otherwise, this method returns NULL.
 * @author vmc <vmc@leftnode.com>
 * @param $key The key or variable to check for existence.
 * @param $array The array or object to search.
 * @retval mixed Returns value if found, NULL otherwise.
 */
function asfw_exists_return($key, $array) {
	if ( true === asfw_exists($key, $array) ) {
		if ( true === is_object($array) ) {
			return $array->$key;
		} else {
			return $array[$key];
		}
	}
	
	return NULL;
}

/**
 * Determines if all keys in $key_list are present in an array or object.
 * @author vmc <vmc@leftnode.com>
 * @param $key_list An array of keys to check.
 * @param $array The array or object to check the keys of.
 * @retval boolean True if all values in $key_list are keys of $array, false otherwise.
 */
function asfw_exists_all($key_list, $array) {
	if ( false === is_array($key_list) ) {
		return false;
	}

	foreach ( $key_list as $key ) {
		if ( false === asfw_exists($key, $array) ) {
			return false;
		}
	}
	
	return true;
}

/**
 * Ensures an array is an actually associative array with string keys.
 * @author vmc <vmc@leftnode.com>
 * @param $array The array to test its associativity.
 * @retval boolean True if $array is associative, false otherwise.
 */
function asfw_is_assoc($array) {
	if ( false === is_array($array) ) {
		return false;
	}
	
	$is_assoc = true;
	$keys = array_keys($array);
	foreach ( $keys as $key ) {
		if ( false === is_string($key) ) {
			$is_assoc = false;
		}
	}
	
	return $is_assoc;
}

/**
 * Takes an array and makes all of values the keys of it, setting the values to
 * true so asfw_exists() can test for the existance of a value instead of in_array().
 * @author vmc <vmc@leftnode.com>
 * @param $a An array to swap.
 * @retval array Returns the swapped array.
 */
function asfw_make_values_keys($a) {
	if ( 0 === count($a) ) {
		return array();
	}
	
	$during = array();
	foreach ( $a as $k => $v ) {
		$during[$v] = true;
	}
	return $during;
}

/**
 * Method to recursively stripslashes from an array. Although Artisan_System is by
 * no means PHP4 compatible, this is essentially a duplicate of array_map_recursive().
 * This method can typically be used to get around magic_quotes.
 * @author vmc <vmc@leftnode.com>
 * @param $array The array to recursively strip the slashes of.
 * @retval array Returns the clean array.
 */
function asfw_stripslashes_recursive($array) {
	foreach ( $array as $k => $v ) {
		if ( true === is_array($v) ) {
			$array[$k] = asfw_stripslashes_recursive($v);
		} else {
			$array[$k] = stripslashes($v);
		}
	}
	return $array;
}

/**
 * Ensure that an array of values exist as keys of an array.
 * @author vmc <vmc@leftnode.com>
 * @param $keys The array of values that should be keys of $array.
 * @param $array The array that should have $keys values as keys.
 * @retval boolean True if all of the $keys in $array exist, false otherwise.
 */
function asfw_array_keys_exist($keys, $array) {
	if ( false === asfw_is_assoc($array) ) {
		return false;
	}
	
	$found = true;
	foreach ( $keys as $k ) {
		// ake() is slightly slower than isset(), however, isset() returns false
		// if the value is NULL, and this method should just check that the key exists,
		// not the specific value of it.
		if ( false === array_key_exists($k, $array) ) {
			$found = false;
			break;
		}
	}
	
	return $found;
}