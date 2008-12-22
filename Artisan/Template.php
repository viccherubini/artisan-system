<?php

/**
 * @see Artisan_Template_Exception
 */
require_once 'Artisan/Template/Exception.php';


/**
 * The Artisan_Template class allows a programmer to use templates with their site.
 * A template can come from any source (the two most common are database and filesystem).
 * After being loaded, the values are replaced with values specified in the code.
 * @author vmc <vmc@leftnode.com>
 * @todo Implement the ability to cache variables and parsed templates.
 */
abstract class Artisan_Template {
	///< The theme to load data from, can be a directory or entry in the database.
	protected $_theme = NULL;
	
	///< The code to parse.
	protected $_template_code = NULL;
	
	///< The parsed code.
	protected $_template_code_parsed = NULL;
	
	///< The name of the specific template to load.
	protected $_template = NULL;
	
	///< The list of variables in this template to replace. Key/value pair array.
	protected $_replace_list = array();
	
	///< Turn debugging on or off, if on, unparsed variables will be left, if off, they will be replaced with nothing.
	protected $_debug_mode = false;
	
	/**
	 * The main constructor for the Artisan_Template class.
	 * @author vmc <vmc@leftnode.com>
	 * @retval object New template instance.
	 */
	public function __construct() {
		
	}

	/**
	 * Destructor for the Artisan_Template class. Destroys all data.
	 * @author vmc <vmc@leftnode.com>
	 * @retval null Does not return a value.
	 */
	public function __destruct() {
		unset($this->_template);
		unset($this->_replace_list);
		unset($this->_template_code_parsed);
	}
	
	/**
	 * Sets the debugging mode. If in debugging mode, unparsed variables will remain, 
	 * otherwise, if not in debugging mode, unparsed variables will be parsed out.
	 * @author vmc <vmc@leftnode.com>
	 * @param $debug_mode Boolean value, sets the current debugging mode.
	 * @retval boolean Returns true;
	 */
	public function setDebugMode($debug_mode) {
		if ( true === is_bool($debug_mode) ) {
			$this->_debug_mode = $debug_mode;
		}
		return true;
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
	public function parse($template, $replace_list = array()) {
		$loaded = $this->_load($template);
		
		if ( false === $loaded ) {
			return false;
		}
		
		$this->_replace_list = $replace_list;
		$this->_parse();
		
		return $this->_template_code_parsed;
	}
	
	/**
	 * Returns the location of the stylesheet this theme should use.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the stylesheet path.
	 */
	//public function getStyleSheet() {
	//	$ss = $this->_theme . '.css';
	//	return $ss;
	//}
	
	/**
	 * Loads a template from the currently set theme.
	 * @author vmc <vmc@leftnode.com>
	 * @param $template The name of the template to load.
	 * @retval string Returns the template code.
	 */
	abstract protected function _load($template);
	
	/**
	 * Parsed out all of the variables in a template.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	protected function _parse() {
		$result_list = array();
		preg_match_all("/\{(\w+)\}/i", $this->_template_code, $result_list, PREG_SET_ORDER);
	
		$result_length = count($result_list);
		$parse_list = array();
		for ( $i=0; $i<$result_length; $i++ ) {
			// This is the string {variable}
			$var = $result_list[$i][0];
			
			// This is the string variable (the key)
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
		
		return true;
	}
}