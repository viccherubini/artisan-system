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

	/**
	 * Loads an XML file from source into a string.
	 * @return Always returns true.
	 * @param $src The filename or string of XML to load.
	 * @author vmc <vmc@leftnode.com>
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
	 * @return A hash array of XML key/value pairs.
	 * @author vmc <vmc@leftnode.com>
	 */
	public static function toArray() {
		$xml_a = self::_parseXml(self::$_xml);
		self::$_xml = NULL;
		return $xml_a;
	}

	/**
	 * Convert the loaded array to XML.
	 * @return A string of XML.
	 * @param $data The array of data to turn into XML.
	 * @author vmc <vmc@leftnode.com>
	 */
	public static function toXml($data) {
		// The $root is the first key of the data
		$root = key($data);
		
		$xml = self::_unparseXml($data, NULL);

		$xml_x  = "<" . $root . ">\n";
		$xml_x .= "\t" . $xml . "\n";
		$xml_x .= "</" . $root . ">";
		return $xml_x;
	}

	/**
	 * Take a multidimensional array and convert it to an XML document.
	 * $usetag is used if a specific tag should be used for opening and
	 * closing each element rather than the tag that comes from the loop.
	 * @return A string of XML.
	 * @author vmc <vmc@leftnode.com>
	 */
	private static function _unparseXml($root, $usetag = NULL) {
		// Not the band name.
		static $x = NULL;

		foreach ( $root as $tag => $value ) {
			if ( true === is_array($value) ) {
				/**
				 * The array_sum(array_keys()) is to test if an array is
				 * returned as a normal array or a hash array.
				 * For example, the following XML:
				 * @code
				 * <class_list>
				 *     <class>PHP_Class1</class>
				 *     <class>PHP_Class2</class>
				 *     <class>PHP_Class3</class>
				 * </class_list>
				 * @endcode
				 * would be stored as so in PHP:
				 * @code
				 * $xml = array(
				 *     'class_list' => array(
				 *         'class' => array(
				 *             0 => PHP_Class1,
				 *             1 => PHP_Class2,
				 *             2 => PHP_Class3
				 *         )
				 *     )
				 * );
				 * @endcode
				 * Clearly, we just don't want the XML to be returned
				 * with the integer keys, so $usetag is used. In this case,
				 * $usetag would be set to 'class' and then passed recursively.
				 * In the resulting else of this function, the code would use
				 * $usetag to create &lt;class&gt;PHP_Class1&lt;/class&gt; rather than
				 * &lt;0&gt;PHP_Class1&lt;/0&gt;. To accomplish this, the keys of the
				 * array are summed. If they are greater than 0, its a pretty
				 * good chance the array is a normal array and not a hash
				 * array, and thus, set the $usetag value.
				 */
				$key_sum = 0;
				$key_sum = array_sum(array_keys($value));

				$usetag = NULL;
				if ( $key_sum > 0 ) {
					$usetag = $tag;
				} else {
					$x .= "<" . $tag . ">\n";
				}
				self::_unparseXml($value, $usetag);
			} else {
				$end_tag = NULL;
				if ( false === empty($usetag) ) {
					$tag = $usetag;
					$end_tag = "</" . $tag . ">\n";
				}

				$x .= "<" . $tag . ">" . $value;
				$x .= $end_tag;
			}

			if ( true === empty($usetag) ) {
				$x .= "</" . $tag . ">\n";
			}
		}
		return $x;
	}

	/**
	 * Parses the XML object into a key/value array.
	 * @return The hash array of XML values.
	 * @author vmc <vmc@leftnode.com> with help from php.net
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