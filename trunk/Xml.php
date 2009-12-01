<?php

require_once 'Func.Library.php';

class Artisan_Xml {
	private static $xml;
	public static $count = 0;

	public static function load($src) {
		if ( true === class_exists('SimpleXMLElement') ) {
			if ( true === is_file($src) && true === is_readable($src) ) {
				self::$xml = simplexml_load_file($src, NULL, LIBXML_NOERROR);
			} else {
				self::$xml = simplexml_load_string($src, NULL, LIBXML_NOERROR);
			}
		}
		return true;
	}

	public static function toArray() {
		$xml_a = self::parseXml(self::$xml);
		return $xml_a;
	}

	public static function toXml($data, $root = NULL) {
		$xml = self::unparseXml($data);
		if ( false === empty($root) ) {
			$xml_x  = "<" . $root . ">" . $xml . "</" . $root . ">";
		} else {
			$xml_x = $xml;
		}
		
		return $xml_x;
	}

	private static function unparseXml($root) {
		$xml_x = NULL;
		foreach ( $root as $tag => $value ) {
			if ( true === is_array($value) ) {
				$sum = array_sum(array_keys($value));
				if ( $sum > 0 ) {
					foreach ( $value as $n_value) {
						$xml_x .= "<" . $tag . ">";
						if ( true === is_array($n_value) ) {
							$xml_x .= self::unparseXml($n_value);
						} else {
							$xml_x .= $n_value;
						}
						$xml_x .= "</" . $tag . ">\n";
					}
				} else {
					$xml_x .= "<" . $tag . ">";
					$xml_x .= self::unparseXml($value);
					$xml_x .= "</" . $tag . ">\n";
				}
			} else {
				$xml_x .= "<" . $tag . ">" . $value . "</" . $tag . ">\n";
			}
		}
		return $xml_x;
	}

	private static function parseXml($root) {
		$x = array();
		if ( true === is_array($root) ) {
			foreach ( $root as $key => $value ) {
				$x[$key] = self::parseXml($value);
			}
		} else {
			if ( true === is_object($root) ) {
				$objvars = get_object_vars($root);
				if ( false === empty($objvars) ) {
					foreach ( $objvars as $key => $value ) {
						$x[$key] = self::parseXml($value);
					}
				}
			} else {
				$x = strval($root);
			}
		}
		return $x;
	}
}