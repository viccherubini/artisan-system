<?php

/**
 * Loads up a template from the filesystem.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Template_Filesystem extends Artisan_Template {
	///< The extension of all template files.
	const TEMPLATE_EXT = '.thm';
	
	///< The name of the directory that holds global templates.
	const THEME_DIRECTORY_GLOBAL = 'global/';
	
	///< The name of the directory that holds the templates.
	private $_theme_directory = 'Themes/';
	
	///< The current name of the theme to load templates for.
	private $_theme_name = NULL;
	
	/**
	 * Constructor for the Artisan_Template class to get the templates from the filesystem.
	 * The configuration parameter passed to this method can overwrite the base template directory.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C Optional configuration object.
	 * @retval Object The new Artisan_Template_Filesystem object.
	 */
	public function __construct(Artisan_Config &$C = NULL) {
		if ( true === is_object($C) ) {
			$this->_theme_directory = $C->theme_directory;
		}
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
		$theme = trim($theme);
		
		if ( true === empty($theme) ) {
			throw new Artisan_Template_Exception(ARTISAN_ERROR_CORE, 'The theme name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$theme = str_replace(array('/', '\\\\'), NULL, $theme);
		
		// Now, ensure the theme actually exists in the filesystem
		$theme .= '/';
		$theme_location = $this->_theme_directory . $theme;
		
		if ( false === is_dir($theme_location) ) {
			throw new Artisan_Template_Exception(ARTISAN_ERROR_CORE, 'The theme location ' . $theme_location . ' is not a directory on the filesystem.', __CLASS__, __FUNCTION__);
		}
		
		$this->_theme = $theme;
		
		return true;
	}
	
	/**
	 * Loads a template from the filesystem.
	 * @author vmc <vmc@leftnode.com>
	 * @param $template The name of the template to load from the currently selected theme.
	 * @retval The unparsed code from the filesystem.
	 */
	protected function _load($template) {
		if ( true === empty($this->_theme) ) {
			return false;
		}
		
		$template = trim($template);
		if ( true === empty($template) ) {
			return false;
		}

		$template_location = $this->_theme_directory . $this->_theme . $template . self::TEMPLATE_EXT;
		
		if ( false === is_file($template_location) ) {
			// If the template sent in isn't in the theme directory, perhaps its global
			// in which case it resides in the self::THEME_DIRECTORY_GLOBAL const.
			$template_location = $this->_theme_directory . self::THEME_DIRECTORY_GLOBAL . $template . self::TEMPLATE_EXT;
			
			if ( false === is_file($template_location) ) {
				return false;
			}
		}
		
		$template_fh = @fopen($template_location, 'r');
		if ( false === $template_fh ) {
			return false;
		}
		$code = fread($template_fh, filesize($template_location));
		fclose($template_fh);
		
		if ( true === empty($code) ) {
			return false;
		}
		
		$this->_template_code = $code;
		
		return $code;
	}
}
