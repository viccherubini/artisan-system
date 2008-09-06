<?php

class Artisan_Template_Database extends Artisan_Template {
	private $DB = NULL;
	private $_theme_id = 0;
	
	const TABLE_THEME = 'artisan_theme';
	const TABLE_THEME_CODE = 'artisan_theme_code';
	
	/**
	 * Constructor for the Artisan_Template class to get the templates from the database. Assumes
	 * the object is already connected to the database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object The new Artisan_Template_Database object.
	 */
	public function __construct(Artisan_Database &$db) {
		/*
		if ( false === $db->isConnected() ) {
			// Try to connect to the database
			try {
				$db->connect();
			} catch ( Artisan_Database_Exception $e ) {
				throw new Artisan_Template_Exception(ARTISAN_ERROR_CORE, $e->getMessage(), __CLASS__, __FUNCTION__);
			}
		}
		*/
		
		// We can only assume the database has a current connection
		// as we don't want to attempt to connect
		$this->DB = &$db;
	}

	public function __destruct() {
		
	}

	public function setTheme($theme) {
		$theme = trim($theme);
		
		if ( true === empty($theme) ) {
			throw new Artisan_Template_Exception(ARTISAN_ERROR_CORE, 'The theme name is empty', __CLASS__, __FUNCTION__);
		}
		
		$theme = trim($theme);
		if ( false === empty($theme) ) {
			$this->_theme = $theme;
		}
		
		$theme_id = $this->DB->select
			->from(self::TABLE_THEME, 'at', 'theme_id')
			->where(array('theme_name' => $theme, 'theme_status' => 1))
			->query()
			->fetch('theme_id');
	
		if ( $theme_id < 1 ) {
			throw new Artisan_Template_Exception(ARTISAN_ERROR_CORE, 'Theme ' . $theme . ' specified was not found', __CLASS__, __FUNCTION__);
		}
		
		$this->_theme_id = $theme_id;
	}
	
	public function parse($template, $replace_list = array()) {
		$loaded = $this->_load($template);
		
		if ( false === $loaded ) {
			return false;
		}
		
		$this->_replace_list = $replace_list;
		
		$this->_parse();
		
		return $this->_template_code_parsed;
	}
	
	public function getThemeId() {
		return $this->_theme_id;
	}
	
	protected function _load($template) {
		if ( true === empty($this->_theme) ) {
			return false;
		}
		
		if ( $this->_theme_id < 1 ) {
			return false;
		}
		
		$code = $this->DB->select
			->from(self::TABLE_THEME_CODE, 'atc', 'code')
			->where(array('code_name' => $template))
			->query()
			->fetch('code');
		
		if ( true === empty($code) ) {
			return false;
		}
		
		$this->_template_code = $code;
		
		return $code;
	}
}

?>
