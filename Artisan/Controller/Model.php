<?php

require_once 'Artisan/Functions/Array.php';

abstract class Artisan_Controller_Model {
	protected $_id = 0;
	protected $_model = array();
	protected $_addl = array();
	
	protected $_hash = NULL;
	
	public function __construct($object_id=0) {
		$this->_load(intval($object_id));
	}
	
	public function __destruct() {
		$this->_model = array();
	}
	
	public function __get($name) {
		if ( true === isset($this->_model[$name]) ) {
			return $this->_model[$name];
		}
		
		if ( true === isset($this->_addl[$name]) ) {
			return $this->_addl[$name];
		}
		//return asfw_exists_return($name, $this->_model);
	}
	
	public function __set($name, $v) {
		$this->_model[$name] = $v;
		return $this;
	}
	
	public function getId() {
		return $this->_id;
	}
	
	public function getArray($list=array(), $ignore=false) {
		$return = $this->_model;
		
		if ( count($list) > 0 ) {
			if ( false === $ignore ) {
				$return = array_intersect_key($this->_model, asfw_make_values_keys($list));
			} else {
				foreach ( $list as $v ) {
					unset($return[$v]);
				}
			}
		}
	
		return $return;
	}

	public function getRegistryHash() {
		if ( true === empty($this->_hash) ) {
			$this->_hash = uniqid('artisanControllerModel.', true);
		}
		return $this->_hash;
	}
	
	public function getRegistryName() {
		return get_class($this);
	}
	
	public function setAdditionalData($addl) {
		$this->_addl = $addl;
	}
	
	public function write() {
		if ( $this->_id > 0 ) {
			$this->_update();
		} else {
			$this->_insert();
		}
		return $this->_id;
	}
	
	protected function _loadFromArray($key, $obj) {
		$this->_id = 0;
		if ( true === asfw_exists($key, $obj) ) {
			$this->_id = $obj[$key];
			unset($obj[$key]);
		}
		
		$this->_model = (array)$obj;
		return $this;
	}
	
	abstract protected function _load($object_id);
	
	abstract protected function _insert();
	
	abstract protected function _update();
}