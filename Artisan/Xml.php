<?php


class Artisan_Xml {
	private static $_xml;
	
	/**
	 * Loads an XML file from source into a string.
	 *
	 */
	public static function load($src) {
		//$src = $this->_source;
		// See if source is a file or not. If it is, SimpleXMLElement
		// can load it. Must be a file on this server though,
		// URL wrappers aren't supported.
		if ( true === class_exists('SimpleXMLElement') ) {
			if ( true === is_file($src) && true === is_readable($src) ) {
				self::$_xml = simplexml_load_file($src, NULL, LIBXML_NOERROR);
			} else {
				self::$_xml = simplexml_load_string($src, NULL, LIBXML_NOERROR);
			}
		}
	}
	
	/**
	 * Convert the loaded XML to an array.
	 */
	public static function toArray() {
		$xml_a = self::_parseXml(self::$_xml);
		
		// Free up some memory.
		self::$_xml = NULL;
		
		return $xml_a;
	}
	
	/**
	 * Convert the loaded array to XML.
	 */
	public static function toXml() {
		
	}
	
	/**
	 * Parses the XML object into a key/value array.
	 */
	private static function _parseXml($root) {
		$x = array();
		
		if ( true === is_array($root) ) {
			foreach ( $root as $key => $value ) {
				$x[$key] = self::_parseXml($value);
			}
		} else {
			if ( true === is_object($root) ) {
				$objvars = get_object_vars($root);
				
				if ( false === empty($objvars) ) {
					foreach ( $objvars as $key => $value ) {
						$x[$key] = self::_parseXml($value);
					}
				}
			} else {
				$x = strval($root);
			}
		}
		
		return $x;
	}
}

?>
