<?php


/**
 * The entry to the shipping modules. This class will control all of the available
 * modules by loading the appropriate ones and querying the appropriate servers.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Shipping {
	private $_moduleList = array();
	private $_quoteList = array();
	public function __construct() {
	
	}

	public function addModule(Artisan_Shipping_Module &$M) {
		// First ensure the module does not exist
		if ( true === asfw_exists($M->id(), $this->_moduleList) ) {
			// Module already exists, return false;
			return false;
		}
		
		$this->_moduleList[$M->id()] = &$M;
		return true;
	}

	public function quote() {
		if ( count($this->_moduleList) < 1 ) {
			throw new Artisan_Shipping_Exception(ARTISAN_WARNING, 'There are no shipping modules loaded.');
		}
		
		$quoteList = array();
		foreach ( $this->_moduleList as $module ) {
			//asfw_print_r($module->quote());
			$quote = $module->quote();
			$moduleList[] = $quote;
		}
		
		asfw_print_r($moduleList);
		return $quoteList;
	}
}