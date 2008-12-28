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
		exit('Finish implementing ' . __CLASS__ . '::' . __FUNCTION__);
		
		if ( false === $ITEM->exists('title', 'link', 'description', 'pubDate', 'author') ) {
			return false;
		}
		
		$this->_item_list[] = $ITEM->toArray();
	}
	
	/**
	 * Sets the mapping between the source and the layout of the RSS feed.
	 * @author vmc <vmc@leftnode.com>
	 * @param $map The mapping array to map the data source and the RSS feed format.
	 * @todo Finish writing this function!
	 */
	public function setMap($map) {
		exit('Finish implementing ' . __CLASS__ . '::' . __FUNCTION__);
	}
	
	/**
	 * Writes the feed data out to the RSS XML.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the XML string.
	 */
	public function write() {
		exit('Finish implementing ' . __CLASS__ . '::' . __FUNCTION__);
		
		$header = array(
			'channel' => array(
				'title' => $this->_channel_title,
				'link' => $this->_channel_link,
				'description' => $this->_channel_description,
				'language' => $this->_channel_language,
				'generator' => $this->_channel_generator,
				'item' => $this->_item_list
			)
		);
	}
	
	/**
	 * Loads up the data from the specified source.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if successful load, false otherwise.
	 */
	abstract public function load();
}