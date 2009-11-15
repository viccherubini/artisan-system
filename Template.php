<?php

class Artisan_Template {
	private $template_code = NULL;
	private $template_code_parsed = NULL;
	private $replace_list = array();
	private $debug_mode = false;
	
	public function __construct($template_code = NULL) {
		$this->setTemplateCode($template_code);
	}

	public function __destruct() {
		unset($this->template_code, $this->replace_list, $this->template_code_parsed);
	}
	
	public function setDebugMode($debug_mode) {
		// If in debug mode, unparsed variable will remain, otherwise, they'll be stripped out.
		if ( true === is_bool($debug_mode) ) {
			$this->debug_mode = $debug_mode;
		}
		return $this;
	}
	
	public function setTemplateCode($template_code) {
		$this->template_code = $template_code;
		return $this;
	}
	
	public function setReplaceList($replace_list) {
		$this->replace_list = $replace_list;
		return $this;
	}
	
	public function parse($replace_list = array()) {
		$this->replace_list = $replace_list;

		$result_list = array();
		preg_match_all("/\{(\w+)\}/i", $this->template_code, $result_list, PREG_SET_ORDER);
	
		$this->template_code_parsed = NULL;
	
		$result_length = count($result_list);
		$parse_list = $empty_list = array();
		for ( $i=0; $i<$result_length; $i++ ) {
			// This is the string {variable}
			$var = $result_list[$i][0];
			
			// This is the string variable (the key)
			$trim_var = $result_list[$i][1];
			
			// The $parse_list is created in order to avoid having to call
			// str_replace() in each iteration of this loop.
			if ( true === isset($this->replace_list[$trim_var]) ) {
				$parse_list[$var] = $this->replace_list[$trim_var];
			} else {
				if ( false === $this->debug_mode ) {
					// $empty_list is a list of variables not found in the variables passed to the parser.
					// At the end, if debug mode is on, these will be removed.
					$empty_list[$var] = NULL;
				}
			}
		}
		
		if ( false === $this->debug_mode ) {
			$this->template_code = str_replace(array_keys($empty_list), $empty_list, $this->template_code);
		}
		
		$this->template_code_parsed = str_replace(array_keys($parse_list), $parse_list, $this->template_code);

		return $this->template_code_parsed;
	}
}