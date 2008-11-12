<?php

/**
 * This class provides a singleton instance to manipulate the controller class.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Controller_Builder {
	///< Since this is a singleton, the instance of this class.
	private static $INST = NULL;
	
	///< The Controller Translation Table array.
	private static $CTT = NULL;
	
	///< The configuration instance for how the Controller should be used.
	private static $CONFIG = NULL;

	///< The instance of the Artisan_Controller class.
	private static $CONTROLLER = NULL;

	///< The vector of arguments from the URL.
	private static $_url_argv = array();
	
	///< The method in the controller class to call.
	private static $_method = NULL;
	
	///< The controller to call.
	private static $_controller = NULL;
	
	/**
	 * Private constructor because this class is a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	private function __construct() { }
	
	/**
	 * Private clone method because this class is a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	private function __clone() { }
	
	/**
	 * Public destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function __destruct() { }
	
	/**
	 * The method to return the instance of this class, also builds the Artisan_Controller
	 * class to access later on. This method provides a singleton interface to this class.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns an instance of the Artisan_Controller_Builder class.
	 */
	public static function &get() {
		if ( true === is_null(self::$INST) ) {
			self::$INST = new self;
			self::_build();
		}
		
		return self::$INST;
	}
	
	/**
	 * Sets the controller's configuration through an Artisan_Config object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $CONFIG An instance of an Artisan_Config object to configure the Artisan_Controller class against.
	 * @retval boolean Returns true.
	 */
	public static function setConfig(Artisan_Config &$CONFIG) {
		// Because this class is a singleton, any time a method is called,
		// since it is called through get(), it guarantees that the controller
		// is already built. Thus, the configuration can be sent to the controller.
		self::$CONFIG = &$CONFIG;
		self::$CONTROLLER->setConfig($CONFIG);
		
		return true;
	}

	/**
	 * Sets the controller translation table to translate url arguments.
	 * @author vmc <vmc@leftnode.com>
	 * @param $CTT The translation table defined in a configuration file.
	 * @retval boolean Returns true.
	 */
	public static function setTranslationTable(&$CTT) {
		self::$CTT = &$CTT;
		
		return true;
	}
	
	/**
	 * Registers an object to the controller class so that way controllers built through
	 * the class can use that object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $PLUGIN An object to register with the Artisan_Controller class.
	 * @param $name The name of the plugin to use to reference later.
	 * @retval boolean Returns true.
	 */
	public static function registerPlugin(&$PLUGIN, $name) {
		if ( true === empty($name) ) {
			throw new Artisan_Controller_Exception(ARTISAN_CORE_ERROR, 'Failed to register plugin, $name is NULL.', __CLASS__, __FUNCTION__);
		}
		
		Artisan_Controller_Plugin::get()->register($PLUGIN, $name);
	}
	
	/**
	 * Executes the specified controller, method, and arguments. This method collects the
	 * URL arguments, loads up the specified controller from those arguments, and then
	 * executes it.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Controller_Exception _parse() throws an error if a method or controller can't be found in the translation table.
	 * @throw Artisan_Controller_Exception load() throws an error if any of the appropriate controller files or classes can't be found.
	 * @throw Artisan_Controller_Exception execute() throws an error if the execution of the method in the controller fails.
	 * @throw Artisan_Controller_Exception Throws the exception thrown by any of _parse(), load(), or execute().
	 * @retval boolean Returns true.
	 */
	public function execute() {
		try {
			self::_parse();
			self::$CONTROLLER->load(self::$_controller);
			self::$CONTROLLER->execute(self::$_method, self::$_url_argv);
		} catch ( Artisan_Controller_Exception $e ) {
			throw $e;
		}
		
		return true;
	}
	
	/**
	 * Builds the Artisan_Controller class.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	private static function _build() {
		self::$CONTROLLER = new Artisan_Controller(self::$CONFIG);
		return true;
	}
	
	/**
	 * Parses the URL PATH_INFO data to get the controller, method, and arguments to use.
	 * @throw Artisan_Controller_Exception Throws an error if a method or controller can't be found in the translation table.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	private static function _parse() {
		/// Get the path info
		$path_info = asfw_exists_return('PATH_INFO', $_SERVER);
		$controller_class = $method = NULL;
		self::$_url_argv = array();
		
		if ( false === empty($path_info) ) {
			// Strip off the leading /
			$path_info = substr($path_info, 1);
			$path_info_bits = explode('/', $path_info);

			// Bit 0 is the controller class.
			// Bit 1 is the method to use in that class.
			// Any further bits are just variables to pass into the class.
			$controller_class = trim($path_info_bits[0]);

			if ( count($path_info_bits) > 1 ) {
				$method = trim($path_info_bits[1]);
				self::$_url_argv = array_slice($path_info_bits, 2);
			} else {
				$method = self::$CONFIG->default_method;
			}

			self::$_method = $method;
			self::$_controller = $controller_class;

			// Look up the data in the translation table.
			if ( true === asfw_exists($controller_class, self::$CTT) ) {
				$CTT_METHOD = self::$CTT[$controller_class];
				if ( true === array_key_exists(self::$_method, $CTT_METHOD) ) {
					$ctt_argv = $CTT_METHOD[self::$_method];
					$ctt_argc = count($ctt_argv);

					// Compare the count of $args and $vars to see 
					// if any extra arguments are trying to get in,
					// if so, remove them.	
					$url_argc = count(self::$_url_argv);

					if ( $ctt_argc > $url_argc ) {
						// Push x elements onto $url_argv.
						$pad_amt = ( $ctt_argc - $url_argc ) + count(self::$_url_argv);
						self::$_url_argv = array_pad(self::$_url_argv, $pad_amt, NULL);
					} elseif ( $ctt_argc < $url_argc ) {
						// Pop x elements off of $url_argv.
						self::$_url_argv = array_slice(self::$_url_argv, 0, $ctt_argc, true);
					}

					// Now that we're sure $url_argv is the correct length, it's time
					// to enforce the actual rules of the translation table.
					reset(self::$_url_argv);
					reset($ctt_argv);
					
					for ( $i=0; $i<$url_argc; $i++ ) {
						$translation_rule = current($ctt_argv);
						if ( false === empty($translation_rule) ) {
							// The translation rule must be a valid regex.
							if ( 0 === preg_match('/' . $translation_rule . '/i', self::$_url_argv[$i]) ) {
								// It's a failure, set the value to NULL.
								self::$_url_argv[$i] = NULL;
							}
						}
					
						self::$_url_argv[$i] = urldecode(self::$_url_argv[$i]);
						next($ctt_argv);
					}
				} else {
					throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'No method found in the translation table for "' . $method . '".');
				}
			} else {
				throw new Artisan_Controller_Exception(ARTISAN_ERROR_CORE, 'No controller found in the translation table for "' . $controller_class . '".');
			}
		} else {
			self::$_method = self::$CONFIG->default_method;
			self::$_controller = self::$CONFIG->default_controller;
		}
		
		return true;
	}
}
