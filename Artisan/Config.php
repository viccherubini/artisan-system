<?php

/**
 * @see Artisan_Config_Exception
 */
require_once 'Artisan/Config/Exception.php';

/**
 * @see Artisan_Vo
 */
require_once 'Artisan/Vo.php';

/**
 * This class holds all configuration data that can come from different sources.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Config extends Artisan_Vo {
	/**
	 * Load the configuration.
	 */
	abstract protected function _load($source);

	/**
	 * Echos out the configuration as a print_r() with a pre wrapped around it.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns a string representation of $this.
	 */
	public function __toString() {
		return asfw_print_r($this, true);		
	}
}