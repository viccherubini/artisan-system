<?php

/**
 * This singleton plugin class allows a programmer to easily register class instances
 * with the main Controller class, giving immediate access to them.
 * @autor vmc <vmc@leftnode.com>
 */
class Artisan_Controller_Plugin {
	private static $INST = NULL;
	
	private function __construct() { }
	
	public function __destruct() { }
	
	private function __clone() { }
	
	public function &get() {
		if ( true === is_null(self::$INST) ) {
			self::$INST = new self;
		}
		
		return self::$INST;
	}
	
	public function register(&$plugin, $name) {
		if ( true === is_object($plugin) ) {
			if ( false === isset($this->{$name}) ) {
				$this->{$name} = &$plugin;
			}
		}
		
		return true;
	}
	
	/*
	public function __get($obj) {
		if ( true === isset($this->$obj) ) {
			return $this->$obj;
		}
		
		return NULL;
	}
	*/
}
