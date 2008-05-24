<?php

class Product {
	public function __construct() {
		echo 'In Product constructor<br />';
	}

	public function get() {
		$str  = 'Product Name: <strong>Some Product Name</strong><br />';
		$str .= '$35.99<br />';

		return $str;
	}
}

?>
