<?php


Artisan_Library::load('Template/Monitor');
Artisan_Library::load('Template/Exception');

abstract class Artisan_Template {
	///< The theme to load data from, can be a directory or entry in the database.
	protected $_theme = NULL;
	
	///< The code to parse.
	protected $_template_code = NULL;
	
	protected $_template_code_parsed = NULL;
	
	///< The name of the specific template to load.
	protected $_template = NULL;
	
	///< The list of variables in this template to replace. Key/value pair array.
	protected $_replace_list = array();
	
	///< Turn debugging on or off, if on, unparsed variables will be left, if off, they will be replaced with nothing.
	protected $_debug_mode = false;
	
	public function __construct() {
		
	}

	public function __destruct() {
		unset($this->_template);
		unset($this->_replace_list);
		unset($this->_template_code_parsed);
	}
	
	
	public function setDebugMode($debug_mode) {
		if ( true === is_bool($debug_mode) ) {
			$this->_debug_mode = $debug_mode;
		}
	}
	
	/**
	 * Sets the current theme.
	 * @author vmc <vmc@leftnode.com>
	 * @param $theme The name of the theme to load from the filesystem or database.
	 * @retval boolean Returns true.
	 */
	abstract public function setTheme($theme);
	
	/**
	 * Loads a template from the currently set theme and
	 * replaces it with variables in $replace_list.
	 * @author vmc <vmc@leftnode.com>
	 * @param $template The name of the template to load from the filesystem or database.
	 * @param $replace_list A hash array of variables to replace. Key is the variable name, value is the replacement value.
	 * @retval string Returns the parsed template.
	 */	
	abstract public function parse($template, $replace_list = array());
	
	abstract protected function _load($template);
	
	protected function _parse() {
		$result_list = array();
		preg_match_all("/\{(\w+)\}/i", $this->_template_code, $result_list, PREG_SET_ORDER);
	
		$result_length = count($result_list);
		$parse_list = array();
		for ( $i=0; $i<$result_length; $i++ ) {
			// this is the string {variable}
			$var = $result_list[$i][0];
			
			// this is the string variable (the key)
			$trim_var = $result_list[$i][1];
			
			// The $parse_list is created in order to avoid having to call
			// str_replace() in each iteration of this loop.
			if ( true === asfw_exists($trim_var, $this->_replace_list) ) {
				$parse_list[$var] = $this->_replace_list[$trim_var];
			}
		}
		
		$pl = array_keys($parse_list);
		$pvl = array_values($parse_list);
		$this->_template_code_parsed = str_replace($pl, $pvl, $this->_template_code);
		
		if ( false === $this->_debug_mode ) {
			$this->_template_code_parsed = preg_replace("/\{(\w+)\}/i", NULL, $this->_template_code_parsed);
		}
	}
}

?>
