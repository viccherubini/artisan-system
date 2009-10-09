<?php

/**
 * @see Artisan_Template
 */
require_once 'Artisan/Template.php';

/**
 * Loads up a template from the filesystem.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Template_Generic extends Artisan_Template {
	/**
	 * Constructor for the Artisan_Template class to get the templates from the filesystem.
	 * The configuration parameter passed to this method can overwrite the base template directory.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C Optional configuration object.
	 * @retval Object The new Artisan_Template_Filesystem object.
	 */
	public function __construct() {
	}

	/**
	 * Destructor to destroy the object.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Object is destroyed.
	 */
	public function __destruct() { }

	/**
	 * Sets the current theme.
	 * @author vmc <vmc@leftnode.com>
	 * @param $theme The name of the theme to load from the filesystem or database.
	 * @throw Artisan_Template_Exception If the $theme string is empty.
	 * @throw Artisan_Template_Exception If the $theme can not be found on the filesystem.
	 * @retval boolean Returns true.
	 */
	public function setTheme($theme) {
		return true;
	}
	
	/**
	 * Loads a template from the filesystem.
	 * @author vmc <vmc@leftnode.com>
	 * @param $template The name of the template to load from the currently selected theme.
	 * @retval The unparsed code from the filesystem.
	 */
	protected function _load($template) {
		return true;
	}
}