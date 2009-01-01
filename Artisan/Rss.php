<?php

/**
 * @see Artisan_Rss_Exception
 */
require_once 'Artisan/Rss/Exception.php';

/**
 * @see Artisan_Xml
 */
require_once 'Artisan/Xml.php';

/**
 * Creates an RSS feed by creating the XML.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Rss {
	///< The title of the channel.
	private $_channel_title;
	
	///< The link (URL) to the channel.
	private $_channel_link;
	
	///< The description of the channel.
	private $_channel_description;
	
	///< The language of the channel, defaults to 'en-us'.
	private $_channel_language = 'en-us';
	
	///< The generator of the channel. Defaults to 'Artisan System Framework'.
	private $_channel_generator = 'Artisan System Framework';
	
	protected $_map = array();
	
	protected $_map_set = false;
	
	///< An array of items to print in the RSS.
	protected $_item_list = array();
	
	/**
	 * Sets the configuration data which must contain the title, link, and description.
	 * @author vmc <vmc@leftnode.com>
	 * @param $CONFIG An instance of the Artisan_Config class.
	 * @throw Artisan_Rss_Exception If the title, link, or description are not present in the $CONFIG.
	 * @retval boolean Returns true.
	 */
	public function setConfig(Artisan_Config &$CONFIG) {
		if ( false === $CONFIG->exists('title', 'link', 'description') ) {
			throw new Artisan_Rss_Exception(ARTISAN_WARNING, 'The config does not require the necessary properties (title, link, and description).', __CLASS__, __FUNCTION__);
		}
		
		$this->_channel_title = htmlentities($CONFIG->title);
		$this->_channel_link = htmlentities($CONFIG->link);
		$this->_channel_description = htmlentities($CONFIG->description);
		if ( false === empty($CONFIG->language) ) {
			$this->_channel_language = trim($CONFIG->language);
		}
		if ( false === empty($CONFIG->generator) ) {
			$this->_channel_generator = htmlentities($CONFIG->generator);
		}
		return true;
	}
	
	/**
	 * Adds an item to the RSS feed.
	 * @author vmc <vmc@leftnode.com>
	 * @param $ITEM A Value Object representation of the item.
	 * @retval boolean True if the item is added, false if the title, link, description, pubDate, or author is not present in the $ITEM.
	 * @todo Finish writing this function!
	 */
	public function addItem(Artisan_Vo $ITEM) {
		if ( false === $ITEM->exists('title', 'link', 'description', 'pubDate', 'author') ) {
			return false;
		}
		
		$this->_item_list[] = $ITEM->toArray();
	}
	
	/**
	 * Sets the mapping between the source and the layout of the RSS feed.
	 * @author vmc <vmc@leftnode.com>
	 * @param $map The mapping array to map the data source and the RSS feed format.
	 * The map should be in the format:
	 * @code
	 * array(
	 *   'title' => 'title_field',
	 *   'description' => 'description_field'
	 *   'author' => 'author_field',
	 *   'pubDate' => 'date_field'
	 * );
	 * @endcode
	 * The title, description, author, and pubDate fields are required. Optionally, 'link'
	 * can be specified if the link is part of the dataset.
	 * @throw Artisan_Rss_Exception If one of the title, description, author, or pubDate fields are empty.
	 * @retval boolean Returns true.
	 */
	public function setMap($map) {
		if ( false === asfw_array_keys_exist(array('title', 'description', 'author', 'pubDate'), $map) ) {
			throw new Artisan_Rss_Exception(ARTISAN_WARNING, 'One of the title, description, author, or pubDates are missing in the $map.', __CLASS__, __FUNCTION__);
		}
		$this->_map = $map;
		$this->_map_set = true;
		return true;
	}
	
	/**
	 * Writes the feed data out to the RSS XML.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the XML string.
	 */
	public function write() {
		$rss = array(
			'channel' => array(
				'title' => $this->_channel_title,
				'link' => $this->_channel_link,
				'description' => $this->_channel_description,
				'language' => $this->_channel_language,
				'generator' => $this->_channel_generator,
				'item' => $this->_item_list
			)
		);
		
		$rss_xml = Artisan_Xml::toXml($rss);
		return $rss_xml;
	}
	
	/**
	 * Loads up the data from the specified source.
	 * @author vmc <vmc@leftnode.com>
	 * @param $urlizer A callback method to generate the URL for each item, should take the item data array as the parameter.
	 * @retval boolean Returns true if successful load, false otherwise.
	 */
	abstract public function load($urlizer);
}