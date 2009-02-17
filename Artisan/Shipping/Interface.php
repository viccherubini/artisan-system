<?php

/**
 * Interface that all shipping modules need to use to have common methods.
 * @author vmc <vmc@leftnode.com>
 */
interface Artisan_Shipping_Module_Interface {
	/**
	 * Returns the name of the module.
	 * @author vmc <vmc@leftnode.com>
	 */
	public function name();
	
	/**
	 * Returns the ID of the module to index in the quote array.
	 * @author vmc <vmc@leftnode.com>
	 */
	public function id();
	
	/**
	 * Performs the acutal quote and returns the quote array.
	 * @author vmc <vmc@leftnode.com>
	 */
	public function quote();
}