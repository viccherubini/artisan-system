<?php


/**
 * This function wraps &lt;pre&gt; tags around an array or object before printing
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
 * Determines if a key exists in an array and isn't empty or a class has a public
 * member variable and isn't empty.
 * @author vmc <vmc@leftnode.com>
 * @param $key The key or variable to check for existence.
 * @param $array The array or object to search.
 * @retval boolean True if the key or variable exists and isn't empty, false otherwise.
 */
function asfw_exists($key, $array) {
	if ( true === is_object($array) ) {
		if ( true === isset($array->$key) ) {
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

?>
