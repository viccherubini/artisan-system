<?php

/**
 * @see Artisan_Template
 */
require_once 'Artisan/Template.php';

/**
 * Loads up a template from the database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Template_Database extends Artisan_Template {
	///< Database instance passed into the class. Assumes the database already has a connection.
	private $DB = NULL;
	
	///< The ID of the theme loaded to use in _load().
	private $_theme_id = 0;
	
	///< The name of the table that holds the main themes
	const TABLE_THEME = 'artisan_theme';
	
	///< The name of the table that holds the theme's code.
	const TABLE_THEME_CODE = 'artisan_theme_code';
	
	/**
	 * Constructor for the Artisan_Template class to get the templates from the database. Assumes
	 * the object is already connected to the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $DB Database object that already has a connection.
	 * @retval Object The new Artisan_Template_Database object.
	 */
	public function __construct(Artisan_Db &$DB) {
		// We can only assume the database has a current connection
		// as we don't want to attempt to connect.
		$this->DB = &$DB;
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
	 * @throw Artisan_Template_Exception If the $theme can not be found in the database.
	 * @retval boolean Returns true.
	 */
	public function setTheme($theme) {
		$theme = trim($theme);
		
		if ( true === empty($theme) ) {
			throw new Artisan_Template_Exception(ARTISAN_ERROR, 'The theme name is empty.');
		}
		
		$tt = self::TABLE_THEME;		
		$result = $this->DB->select()
			->from($tt, asfw_create_table_alias($tt), 'theme_id')
			->where('theme_name = ?', $theme)->where('theme_status = 1')
			->query();
		if ( 1 == $result->numRows() ) {
			$theme_id = $result->fetch('theme_id');
		}
	
		if ( $theme_id < 1 ) {
			throw new Artisan_Template_Exception(ARTISAN_ERROR, 'Theme ' . $theme . ' specified was not found in the table `' . $ttc . '`.');
		}
		
		$this->_theme = $theme;
		$this->_theme_id = $theme_id;
		return true;
	}

	/**
	 * Returns the current theme_id from the database. Note: This method is only
	 * available in this class.
	 * @author vmc <vmc@leftnode.com>
	 * @retval integer The ID of the currently selected theme.
	 */
	public function getThemeId() {
		return $this->_theme_id;
	}
	
	/**
	 * Loads a template from the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $template The name of the template to load from the currently selected theme.
	 * @retval The unparsed code from the database.
	 */
	protected function _load($template) {
		if ( true === empty($this->_theme) ) {
			return false;
		}
		
		if ( $this->_theme_id < 1 ) {
			return false;
		}
		
		$template = trim($template);
		if ( true === empty($template) ) {
			return false;
		}
		
		$ttc = self::TABLE_THEME_CODE;
		$result = $this->DB->select()
			->from($ttc, asfw_create_table_alias($ttc), 'code')
			->where('code_name = ?', $template)
			->query();
		
		if ( 1 == $result->numRows() ) {
			$code = $result->fetch('code');
		}
		
		if ( true === empty($code) ) {
			return false;
		}
		
		$this->_template_code = $code;
		return $code;
	}
}