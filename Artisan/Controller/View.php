<?php

/**
 * @see Artisan_Controller
 */
require_once 'Artisan/Controller.php';

/**
 * Loads up a view specified by the controller method and execute its, trapping
 * its return data and allowing the controller to continue handling it.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Controller_View {
	///< The default directory the views are located in.
	private $_views_dir = 'views';
	
	///< The default directory that the layouts are located in.
	private $_layout_dir = 'layout';
	
	///< The directory that the controllers are located in.
	private $_controller_dir = NULL;
	
	///< The default extension of the views and layouts.
	private $_ext = '.phtml';
	
	///< The name of the view to load.
	protected $_view = NULL;

	///< The directory separator native to the host operating system.
	private $_ds = NULL;

	///< The directory that JavaScript files are stored, this can't be changed with a method.
	private $_js_dir = 'javascript';
	
	///< The directory that image files are stored, this can't be changed with a method.
	private $_images_dir = 'images';
	
	///< The directory that CSS files are stored, this can't be changed with a method.
	private $_css_dir = 'css';

	///< The name of the Controller to use.
	private $_controller = NULL;
	
	///< The root directory, which is $_controller_dir . $_ds;
	private $_root_dir = NULL;
	
	/**
	 * Default constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns a new View object.
	 */
	public function __construct() {
		$this->_ds = DIRECTORY_SEPARATOR;
	}
	
	/**
	 * Sets the controller directory. This is prefixed with __ so that way it won't
	 * be overwritten in any of the Controller classes. It is public because 
	 * Artisan_Controller needs to access it.
	 * @author vmc <vmc@leftnode.com>
	 * @param $cdir The controller directory.
	 * @retval boolean Returns true.
	 */
	public function __setControllerDirectory($cdir) {
		$this->_controller_dir = trim($cdir);
		return true;
	}
	
	/**
	 * Sets the views directory. This is prefixed with __ so that way it won't
	 * be overwritten in any of the Controller classes. It is public because 
	 * Artisan_Controller needs to access it.
	 * @author vmc <vmc@leftnode.com>
	 * @param $vdir The views directory.
	 * @retval boolean Returns true.
	 */
	public function __setViewsDirectory($vdir) {
		$this->_views_dir = trim($vdir);
	}
	
	/**
	 * Sets the layout directory. This is prefixed with __ so that way it won't
	 * be overwritten in any of the Controller classes. It is public because 
	 * Artisan_Controller needs to access it.
	 * @author vmc <vmc@leftnode.com>
	 * @param $ldir The layout directory.
	 * @retval boolean Returns true.
	 */
	public function __setLayoutDirectory($ldir) {
		$this->_layout_dir = trim($ldir);
	}
	
	/**
	 * This function is responsible for loading up the appropriate method and 
	 * executing the values. $__content will be set in here, and because the
	 * Controller classes extend this class, this class will have access to their values.
	 * @author vmc <vmc@leftnode.com>
	 * @param $controller The name of the controller to execute.
	 * @param $view The name of the view to execute.
	 * @throw Artisan_Controller_Exception If the layout name is missing.
	 * @throw Artisan_Controller_Exception If the view file does not exist.
	 * @throw Artisan_Controller_Exception If the layout file does not exist.
	 * @retval string Returns the loaded string data.
	 */
	public function __execute($controller, $view) {
		$ds = $this->_ds;

		if ( false === empty($this->_view) ) {
			$view = $this->_view;
		}
		
		$controller = asfw_rename_controller($controller);
		$view = asfw_rename_controller_method($view);

		if ( true === empty($this->_layout) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'The layout name is empty.', __CLASS__, __FUNCTION__);
		}

		// See if Controllers/$controller/Views/$view.phtml exists, if not, 
		// look in Controllers/Views/$view.phtml. If that doesn't exist, throw an error.
		$this->_root_dir = $this->_controller_dir . $ds;
		$view_file = $this->_root_dir . $controller . $ds . $this->_views_dir . $ds . $view . $this->_ext;
		if ( false === is_file($view_file) ) {
			$view_file = $this->_root_dir . $this->_views_dir . $ds . $view . $this->_ext;
		}
		
		if ( false === is_file($view_file) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'The view file ' . $view_file . ' does not exist.', __CLASS__, __FUNCTION__);
		}
		
		// See if Controllers/$controller/Layout/$layout.phtml exists, if not, 
		// look in Controllers/Layout/$layout.phtml. If that doesn't exist, throw an error.
		$layout_file = $this->_root_dir . $controller . $ds . $this->_layout_dir . $ds . $this->_layout . $this->_ext;
		if ( false === is_file($layout_file) ) {
			$layout_file = $this->_root_dir . $this->_layout_dir . $ds . $this->_layout . $this->_ext;
		}

		if ( false === is_file($layout_file) ) {
			throw new Artisan_Controller_Exception(ARTISAN_ERROR, 'The layout file ' . $layout_file . ' does not exist.', __CLASS__, __FUNCTION__);
		}
		
		$this->_controller = $controller;
		
		// First load up the view
		ob_start();
		require_once $view_file;
		$this->layout_content = ob_get_clean();
		
		// Now load up the layout
		ob_start();
		require_once $layout_file;
		return ob_get_clean();
	}
	
	/**
	 * Writes a JS inclusion line out to the view.
	 * @author vmc <vmc@leftnode.com>
	 * @param $js_file The JavaScript filename to include.
	 * @retval string The <script> tag with the filename included.
	 */
	public function js($js_file) {
		$ds = $this->_ds;
		if ( 0 == preg_match('/\.js$/i', $js_file) ) {
			$js_file .= '.js';
		}
		
		// Unfortunately, this must be calculated each time because the JS file may
		// exist in either of the possible directories.
		$js_tag = NULL;
		$javascript_file = $this->_root_dir . $this->_controller . $ds . $this->_js_dir . $ds . $js_file;
		if ( false === is_file($javascript_file) ) {
			$javascript_file = $this->_root_dir . $this->_js_dir . $ds . $js_file;
		}
		
		if ( true === is_file($javascript_file) ) {
			$js_tag  = '<script src="' . $javascript_file . '"></script>';
			$js_tag .= "\n";
		}
		return $js_tag;
	}
	
	/**
	 * Writes a CSS inclusion line out to the view. Uses a <link> tag.
	 * @author vmc <vmc@leftnode.com>
	 * @param $css_file The CSS filename to include.
	 * @param $media The type of media being outputted.
	 * @param $xhtml If true, echos a /&gt; end, otherwise a &gt; end.
	 * @retval string The <link> tag with the filename included.
	 */
	public function css($css_file, $media = 'screen', $xhtml = true) {
		$ds = $this->_ds;
		if ( 0 == preg_match('/\.css$/i', $css_file) ) {
			$css_file .= '.css';
		}
		
		$css_tag = NULL;
		$stylesheet_file = $this->_root_dir . $this->_controller . $ds . $this->_css_dir . $ds . $css_file;
		if ( false === is_file($stylesheet_file) ) {
			$stylesheet_file = $this->_root_dir . $this->_css_dir . $ds . $css_file;
		}
		
		if ( true === is_file($stylesheet_file) ) {
			if ( true === empty($media) ) {
				$media = 'screen';
			}
		
			$link_tag = '<link type="text/css" rel="stylesheet" href="' . $stylesheet_file . '" media="' . $media . '"';
			if ( true === $xhtml ) {
				$link_tag .= " />\n";
			} else {
				$link_tag .= ">\n";
			}
		}
		return $link_tag;
	}
	
	/**
	 * Writes an image line out to the view. Uses the <img> tag.
	 * @author vmc <vmc@leftnode.com>
	 * @param $image_file The image file to use.
	 * @param $alt The alt attribute value, uses the filename if NULL.
	 * @param $xhtml If true, echos a /&gt; end, otherwise a &gt; end.
	 * @retval string The <img> tag with the filename included.
	 * @todo Add ability to have additional parameters.
	 */
	public function image($img_file, $alt = NULL, $xhtml = true) {
		$ds = $this->_ds;
		
		$img_tag = NULL;
		$image_file = $this->_root_dir . $this->_controller . $ds . $this->_images_dir . $ds . $img_file;
		
		if ( false === is_file($image_file) ) {
			$image_file = $this->_root_dir . $this->_images_dir . $ds . $img_file;
		}

		if ( true === is_file($image_file) ) {
			$alt = htmlentities($alt);
			if ( true === empty($alt) ) {
				$alt = htmlentities($img_file);
			}
			
			$img_tag = '<img src="' . $image_file . '" alt="' . $alt . '"';
			if ( true === $xhtml ) {
				$img_tag .= " />\n";
			} else {
				$img_tag .= ">\n";
			}
		}
		return $img_tag;
	}
}