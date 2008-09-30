<?php


class Artisan_Controller_Builder {
	private static $INST = NULL;
	
	private static $CTT = NULL;
	private static $CONFIG = NULL;

	private static $CONTROLLER = NULL;


	private function __construct() {
	
	}
	
	private function __clone() {
	
	}
	
	public static function get() {
		if ( true === is_null(self::$INST) ) {
			self::$INST = new self;
		}
		
		$this->_build();
		
		return self::$INST;
	}
	
	public function setConfig(Artisan_Config &$CONFIG) {
		$this->CONFIG = &$CONFIG;
	}

	public function setTranslationTable(Artisan_VO &$CTT) {
		$this->CTT = &$CTT;
	}
	
	
	public function registerPlugin(&$PLUGIN, $name) {
		if ( true === empty($name) ) {
			throw new Artisan_Controller_Exception(ARTISAN_CORE_ERROR, 'Failed to register plugin, $name is NULL.', __CLASS__, __FUNCTION__);
		}
		
		Artisan_Controller_Plugin::get()->register($PLUGIN, $name);
	}
	
	public function execute() {
	
	}
	
	private function _build() {
		self::$CONTROLLER = new Artisan_Controller(self::$CONFIG);
	}
}

?>
