<?php

/**
 * Easily perform template string replacements. This class is very basic,
 * and just replaces {keys} in braces with values.
 * @author vmc <vmc@leftnode.com>
 * @todo Implement the ability to cache variables and parsed templates.
 */
class Artisan_Template {
	///< The code to parse.
	protected $_template_code = NULL;
	
	///< The parsed code.
	protected $_template_code_parsed = NULL;
	
	///< The list of variables in this template to replace. Key/value pair array.
	protected $_replace_list = array();
	
	///< Turn debugging on or off, if on, unparsed variables will be left, if off, they will be replaced with nothing.
	protected $_debug_mode = false;
	
	/**
	 * The main constructor for the Artisan_Template class.
	 * @author vmc <vmc@leftnode.com>
	 * @retval object New template instance.
	 */
	public function __construct($template_code = NULL) {
		$this->setTemplateCode($template_code);
	}

	/**
	 * Destructor for the Artisan_Template class. Destroys all data.
	 * @author vmc <vmc@leftnode.com>
	 * @retval null Does not return a value.
	 */
	public function __destruct() {
		unset($this->_template_code, $this->_replace_list, $this->_template_code_parsed);
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
		return $this;
	}
	
	public function setTemplateCode($template_code) {
		$this->_template_code = $template_code;
		return $this;
	}
	
	public function setReplaceList($replace_list) {
		$this->_replace_list = $replace_list;
		return $this;
	}
	
	/**
	 * Loads a template from the currently set theme and
	 * replaces it with variables in $replace_list.
	 * @author vmc <vmc@leftnode.com>
	 * @param $template The name of the template to load from the filesystem or database.
	 * @param $replace_list A hash array of variables to replace. Key is the variable name, value is the replacement value.
	 * @retval string Returns the parsed template.
	 */	
	public function parse($replace_list = array()) {
		$this->_replace_list = $replace_list;
		$this->_parse();
		return $this->_template_code_parsed;
	}
	
	/**
	 * Parsed out all of the variables in a template. Sets the internal code to be the
	 * parsed code.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	protected function _parse() {
		$result_list = array();
		preg_match_all("/\{(\w+)\}/i", $this->_template_code, $result_list, PREG_SET_ORDER);
	
		$this->_template_code_parsed = NULL;
	
		$result_length = count($result_list);
		$parse_list = $empty_list = array();
		for ( $i=0; $i<$result_length; $i++ ) {
			// This is the string {variable}
			$var = $result_list[$i][0];
			
			// This is the string variable (the key)
			$trim_var = $result_list[$i][1];
			
			// The $parse_list is created in order to avoid having to call
			// str_replace() in each iteration of this loop.
			if ( true === isset($this->_replace_list[$trim_var]) ) {
				$parse_list[$var] = $this->_replace_list[$trim_var];
			} else {
				if ( false === $this->_debug_mode ) {
					// $empty_list is a list of variables not found in the variables passed to the parser.
					// At the end, if debug mode is on, these will be removed.
					$empty_list[$var] = NULL;
				}
			}
		}
		
		if ( false === $this->_debug_mode ) {
			$this->_template_code = str_replace(array_keys($empty_list), $empty_list, $this->_template_code);
		}
		
		$this->_template_code_parsed = str_replace(array_keys($parse_list), $parse_list, $this->_template_code);

		return true;
	}
}