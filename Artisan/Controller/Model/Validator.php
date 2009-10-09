<?php

require_once 'Artisan/Validate/Email.php';

abstract class Artisan_Controller_Model_Validator {
	protected $_model = array();
	protected $_model_data = array();
	protected $_model_name = NULL;
	
	protected $_error_list = array();
	
	public function __construct() {
		$this->_model = array();
	}
	
	public function __destruct() {
	
	}
	
	public function load($model_name) {
		$this->init($model_name);
		return $this;
	}
	
	public function validate() {
		$model = (array)asfw_exists_return($this->_model_name, $this->_model);
		$len_model = count($model);
		$len_data = count($this->_model_data);
		
		if ( 0 == $len_model || 0 == $len_data ) {
			return false;
		}
		
		$this->_error_list = array();
		
		// First ensure the keys of the model are equal to the keys of the data.
		// This prevents fake fields from being added through Firebug.
		$model_keys = array_keys($model);
		$data_keys = array_keys($this->_model_data);
		
		$bad_key = false;
		foreach ( $data_keys as $k ) {
			if ( false === array_key_exists($k, $model) ) {
				$bad_key = true;
			}
		}
		
		if ( true == $bad_key ) {
			throw new Artisan_Controller_Exception('One or more fields submitted were maligned. Please resubmit the form.');
		}
		
		$success = true;
		foreach ( $model as $key => $list ) {
			$data_value = asfw_exists_return($key, $this->_model_data);
			
			$label = asfw_exists_return('label', $list);
			$rule_list = (array)asfw_exists_return('rule_list', $list);
			
			$error = NULL;
			
			if ( count($rule_list) > 0 ) {
				foreach ( $rule_list as $type => $rule_value ) {
					switch ( $type ) {
						case 'min_length': {
							$min_length = intval($rule_value);
							if ( strlen($data_value) < $min_length ) {
								$error = sprintf('The field <strong>%s</strong> must have a length greater than or equal to <strong>%d</strong> characters.', $label, $min_length);
							}
							break;
						}
						
						case 'max_length': {
							$max_length = intval($rule_value);
							if ( strlen($data_value) > $max_length ) {
								$error = sprintf('The field <strong>%s</strong> must have a length less than or equal to <strong>%d</strong> characters.', $label, $max_length);
							}
							break;
						}
						
						case 'not_empty': {
							if ( true === empty($data_value) ) {
								$error = sprintf('The field <strong>%s</strong> cannot be empty.', $label);
							}
							break;
						}
						
						case 'not_empty_dropdown': {
							if ( true === empty($data_value) ) {
								$error = sprintf('Please select a value from the <strong>%s</strong> dropdown list.', $label);
							}
							break;
						}
						
						case 'not_zero': {
							$data_value = intval($data_value);
							if ( 0 == $data_value ) {
								$error = sprintf('The field <strong>%s</strong> can not have a value of 0', $label);
							}
							break;
						}
						
						case 'numeric': {
							if ( false === is_numeric($data_value) ) {
								$error = sprintf('The field <strong>%s</strong> must have a numeric value.', $label);
							}
							break;
						}
						
						case 'email': {
							$emailValidator = new Artisan_Validate_Email($data_value);
							if ( false === $emailValidator->isValid() ) {
								$error = sprintf('The field <strong>%s</strong> is not a valid e-mail address.', $label);
							}
							break;
						}
						
						case 'in_array': {
							if ( false === is_array($rule_value) ) {
								$rule_value = array($rule_value);
							}
							
							if ( false === in_array($data_value, $rule_value) ) {
								$error = sprintf('The field <strong>%s</strong> cannot be empty.', $label);
							}
						}
					}
					
					if ( false === empty($error) ) {
						$success = false;
						$this->_error_list[$key] = $error;
						break;
					}
				}
			}
		}
		
		if ( false === $success ) {
			throw new Artisan_Controller_Exception('Sorry, your form submission failed to validate. Please see each field for reasons why.');
		}
		
		return true;
	}
	
	public function setData(array $model) {
		$this->_model_data = $model;
		return $this;
	}
	
	public function getModel() {
		return $this->_model;
	}
	
	public function getErrorList() {
		return $this->_error_list;
	}
	
	abstract public function init($model);
}