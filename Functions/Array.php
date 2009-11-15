<?php



/**
 * If an element exists in an array or object, it returns it's value
 * otherwise in a safe manner (htmlentities and stripslashes), otherwise this method returns NULL.
 * @author vmc <vmc@leftnode.com>
 * @param $key The key or variable to check for existence.
 * @param $array The array or object to search.
 * @retval mixed Returns value if found, NULL otherwise.
 */
function asfw_exists_return_safe($key, $array) {
	return htmlentities(asfw_exists_return($key, $array));
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

/**
 * Returns an array from $hash, an associative array, in which the keys match
 * the values in $keys, preserving the original keys in $hash. In code:
 * @code
 * $keys = array('a', 'b', 'c');
 * $hash = array('a' => 90, 'b' => 99, 'c' => 88, 'd' => 89, 'e' => 50);
 * $hash = asfw_array_slice_keys($keys, $hash);
 * $hash is now array('a' => 90, 'b' => 99, 'c' => 88);
 * @endcode
 * @author vmc <vmc@leftnode.com>
 * @param $keys An array of keys to slice from $hash.
 * @param $hash An associative array to slice from.
 * @retval array The sliced array, or any empty array if no keys match.
 */
function asfw_array_slice_keys($keys, $hash) {
	if ( false === is_array($keys) ) {
		return array();
	}
	
	$final = array();
	$len = count($keys);
	for ( $i=0; $i<$len; $i++ ) {
		if ( true === isset($keys[$i], $hash) ) {
			$final[$keys[$i]] = $hash[$keys[$i]];
		} else {
			$final[$keys[$i]] = NULL;
		}
	}
	return $final;
}

/**
 * Returns true if an array is empty regardless of if it has keys or not. Returns
 * true if all of the values of the array are empty, false otherwise.
 * @author vmc <vmc@leftnode.com>
 * @param $a The array to test.
 * @retval boolean Returns true if the array is truely empty, false otherwise.
 */
function asfw_empty($a) {
	$empty = true;
	if ( true === is_array($a) ) {
		foreach ( $a as $i => $k ) {
			if ( false === empty($k) ) {
				$empty = false;
			}
		}
	}
	return $empty;
}