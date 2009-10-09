<?php

/**
 * Handles all XML data, including parsing XML into a hash array and taking
 * a hash array and turning it back into XML. This requires the SimpleXML
 * class of PHP to be installed to work properly.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Xml {
	///< The XML to be loaded from the SimpleXMLElement PHP class.
	private static $XML;
	
	public static $count = 0;
	/**
	 * Loads an XML file from source into a SimpleXml object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $src The filename or string of XML to load.
	 * @return Always returns true.
	 */
	public static function load($src) {
		// See if source is a file or not. If it is, SimpleXMLElement
		// can load it. Must be a file on this server though,
		// URL wrappers aren't supported.
		if ( true === class_exists('SimpleXMLElement') ) {
			if ( true === is_file($src) && true === is_readable($src) ) {
				self::$XML = simplexml_load_file($src, NULL, LIBXML_NOERROR);
			} else {
				self::$XML = simplexml_load_string($src, NULL, LIBXML_NOERROR);
			}
		}
		return true;
	}

	/**
	 * Convert the loaded XML to an array.
	 * @author vmc <vmc@leftnode.com>
	 * @return A hash array of XML key/value pairs.
	 */
	public static function toArray() {
		$xml_a = self::_parseXml(self::$XML);
		//self::$XML = NULL;
		return $xml_a;
	}

	/**
	 * Convert the loaded array to XML.
	 * @author vmc <vmc@leftnode.com>
	 * @param $data The array of data to turn into XML.
	 * @param $root Optional parameter to specify the root tag.
	 * @return A string of XML.
	 */
	public static function toXml($data, $root = NULL) {
		$xml = self::_unparseXml($data);
		if ( false === empty($root) ) {
			$xml_x  = "<" . $root . ">" . $xml . "</" . $root . ">";
		} else {
			$xml_x = $xml;
		}
		
		return $xml_x;
	}

	/**
	 * Take a multidimensional array and convert it to an XML document.
	 * $usetag is used if a specific tag should be used for opening and
	 * closing each element rather than the tag that comes from the loop.
	 * @author rafshar <rafshar@gmail.com>
	 * @param $root The root of the XML array to begin building.
	 * @return A string of XML.
	 */
	private static function _unparseXml($root) {
		$xml_x = NULL;
		foreach ( $root as $tag => $value ) {
			if ( true === is_array($value) ) {
				$sum = array_sum(array_keys($value));
				if ( $sum > 0 ) {
					foreach ( $value as $n_value) {
						$xml_x .= "<" . $tag . ">";
						if ( true === is_array($n_value) ) {
							$xml_x .= self::_unparseXml($n_value);
						} else {
							$xml_x .= $n_value;
						}
						$xml_x .= "</" . $tag . ">\n";
					}
				} else {
					$xml_x .= "<" . $tag . ">";
					$xml_x .= self::_unparseXml($value);
					$xml_x .= "</" . $tag . ">\n";
				}
			} else {
				$xml_x .= "<" . $tag . ">" . $value . "</" . $tag . ">\n";
			}
		}
		return $xml_x;
	}

	/**
	 * Parses the XML object into a key/value array.
	 * @author vmc <vmc@leftnode.com> with help from php.net
	 * @param $root The root of the XML object to allow recursion.
	 * @return The hash array of XML values.
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


