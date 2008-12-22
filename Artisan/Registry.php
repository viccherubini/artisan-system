<?php

require_once 'Artisan/Functions/Array.php';

/**
 * A registry class that holds data in a global scope. This class is static.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Registry {
	///< A list of objects or variables to store.
	private static $obj_list = array();

	/**
	 * Push a new object or variable onto the registry stack.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of variable to push, does not have to be the same as the variable name.
	 * @param $obj The object or variable to push.
	 * @param $force Force the variable onto the stack even if it already exists.
	 * @retval boolean Returns true.
	 */
	public static function push($name, $obj, $force = false) {
		$name = trim($name);
		
		$found = asfw_exists($name, self::$obj_list);
		if ( (true === $found && true === $force) || false === $found ) {
			self::$obj_list[$name] = $obj;
		}
		return true;
	}
	
	/**
	 * Pops an element from the registry stack. Note, this does not remove the object
	 * unless explicitly called to.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of variable to pop.
	 * @param $remove If true, removes the element from the stack.
	 * @retval mixed Returns the object if found, NULL otherwise.
	 */
	public static function pop($name, $remove = false) {
		$ret = NULL;
		if ( true === asfw_exists($name, self::$obj_list) ) {
			$ret = self::$obj_list[$name];
			
			if ( true === $remove ) {
				unset(self::$obj_list[$name]);
			}
		}
		return $ret;
	}
}