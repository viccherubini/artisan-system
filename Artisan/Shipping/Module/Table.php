<?php

require_once 'Artisan/Shipping/Exception.php';
require_once 'Artisan/Shipping/Module.php';
require_once 'Artisan/Shipping/Interface.php';

class Artisan_Shipping_Module_Table extends Artisan_Shipping_Module implements Artisan_Shipping_Module_Interface {
	private $_id = 'table';
	private $_name = 'Table Shipping';
	private $_table = array();
	
	public function __construct(Artisan_Config &$C = NULL) {
		if ( false === is_null($C) ) {
			if ( true === $C->key('table') ) {
				$this->setTable($C->table);
			}
		}
	}
	
	public function name() {
		return $this->_name;
	}
	
	public function id() {
		return $this->_id;
	}
	
	/**
	 * Sets the table of rates. Each key of the table should be the
	 * maximum weight with a rate. For example, if there are three
	 * indexes, 4.5, 7, and 10, everything between 0 and 4.5 (inclusive) 
	 * would be that rate, everything greater than 4.5 and less than or equal to
	 * 7 will be the second price, and so on. Any weight above the last index
	 * would be that price. Obviously, each weight should be a string, but
	 * return is_numeric($weight) as true.
	 * @author vmc <vmc@leftnode.com>
	 * @code
	 * $table = array(
	 *   '4.5'  => 7.56,
	 *   '7'    => 19.56,
	 *   '10.6' => 34.98
	 * );
	 * @endcode
	 * @param $table The table of different weights and their prices.
	 * @retval boolean True if the table rate could be set, false otherwise.
	 */
	public function setTable($table) {
		if ( count($table) < 1 ) {
			return false;
		}
		
		$failed_table = false;
		foreach ( $table as $weight => $value ) {
			$value = floatval($value);
			if ( false === is_numeric($weight) || $value < 0 ) {
				$failed_table = true;
			}
		}
		
		if ( true === $failed_table ) {
			return false;
		}
		
		$this->_table = $table;
	}
	
	public function quote() {
		// No need for the address, this is just a flat value(s).
		if ( count($this->_table) < 1 ) {
			throw new Artisan_Shipping_Exception(ARTISAN_WARNING, 'The shipping table is not configured properly, it is empty.');
		}
		
		$price = 0.0;
		foreach ( $this->_table as $weight => $value ) {
			if ( $weight <= $this->_weight ) {
				$price = $value;
				break;
			}
		}
		
		echo $price;
		return $quote;
	}
}