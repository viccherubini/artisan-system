<?php

/**
 * This singleton plugin class allows a programmer to easily register class instances
 * with the main Controller class, giving immediate access to them.
 * @autor vmc <vmc@leftnode.com>
 */
class Artisan_Controller_Plugin {
	///< Because this class is a singleton, the instance of this class.
	private static $INST = NULL;
	
	/**
	 * Private constructor because this class is a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	private function __construct() { }
	
	/**
	 * Private clone method because this class is a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	private function __clone() { }
	
	/**
	 * Public destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function __destruct() { }
	
	/**
	 * Returns this class for usage as a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns the itself.
	 */
	public function &get() {
		if ( true === is_null(self::$INST) ) {
			self::$INST = new self;
		}
		
		return self::$INST;
	}
	
	/**
	 * Register a new object into this class.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function register(&$plugin, $name) {
		if ( true === is_object($plugin) ) {
			if ( false === isset($this->{$name}) ) {
				$this->{$name} = &$plugin;
			}
		}
		
		return true;
	}
}