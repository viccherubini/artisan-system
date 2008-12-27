<?php

/**
 * @see Artisan_Rss_Exception
 */
require_once 'Artisan/Rss/Exception.php';

/**
 * @see Artisan_Xml
 */
require_once 'Artisan/Xml.php';
require_once 'Artisan/Functions/Array.php';

abstract class Artisan_Rss {
	private $_channel_title;
	private $_channel_link;
	private $_channel_description;
	private $_channel_language = 'en-us';
	private $_channel_generator;
	
	protected $_item_list = array();
	
	public function setConfig(Artisan_Config &$CONFIG) {
		if ( false === $CONFIG->exists('title', 'link', 'description', 'generator') ) {
			throw new Artisan_Rss_Exception(ARTISAN_WARNING, 'The config does not require the necessary properties (title, link, description, and generator).', __CLASS__, __FUNCTION__);
		}
		
		$this->_channel_title = htmlentities($CONFIG->title);
		$this->_channel_link = htmlentities($CONFIG->link);
		$this->_channel_description = htmlentities($CONFIG->description);
		if ( false === empty($CONFIG->language) ) {
			$this->_channel_language = trim($CONFIG->language);
		}
		$this->_channel_generator = htmlentities($CONFIG->generator);
		
		return true;
	}
	
	
	
	public function addItem(Artisan_Vo $ITEM) {
		if ( false === $ITEM->exists('title', 'link', 'description', 'pubDate', 'author') ) {
			return false;
		}
		
		//$this->_item_list = array('item' => $ITEM->toArray());
		$this->_item_list[] = $ITEM->toArray();
	}
	
	
	
	public function setMap($map) {
		/*
		this array corresponds to the database tables.
		
		*/
	}
	
	abstract public function load();
	
	public function write() {
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
		
		asfw_print_r($header);
		
		$header_xml = Artisan_Xml::toXml($header);
		asfw_print_r($header_xml);
		
		//$item_xml = Artisan_Xml::toXml($this->_item_list, NULL);
		
		
	}
}