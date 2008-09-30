<?php


class Artisan_Controller_Builder {
	private static $INST = NULL;
	
	private static $CTT = NULL;
	private static $CONFIG = NULL;

	


	private function __construct() {
	
	}
	
	private function __clone() {
	
	}
	
	public static function get() {
		if ( true === is_null(self::$INST) ) {
			self::$INST = new self;
		}
		
		
		
		return self::$INST;
	}
	
	public function setConfig(Artisan_Config &$CONFIG) {
		$this->CONFIG = &$CONFIG;
	}

	public function setTranslationTable(Artisan_VO &$CTT) {
		$this->CTT = &$CTT;
	}
}

?>
