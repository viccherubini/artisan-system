<?php

Artisan_Library::load('Config/Monitor');
Artisan_Library::load('Config/Exception');

abstract class Artisan_Config {

	abstract public function set($cfg);
	abstract public function load();
	

	//abstract public function write();
	//abstract public function setValue();
	
	public static function internalize($root, &$t) {
		foreach ( $root as $k => $v ) {
			if ( true === is_array($v) ) {
				$t->$k = $t;
				self::internalize($v, $t);
			} else {
				$t->$k = $v;
			}
		}
	}
}

?>