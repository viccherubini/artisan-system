<?php

Artisan_Library::load('Email/Exception');

/**
 * This abstract class allows email to be sent several ways: through Sendmail/PHP
 * and through SMTP. Support for IMAP to come.
 * @author vmc <vmc@leftnode.com>
 * @todo Finish implementing this class!
 */
abstract class Artisan_Email {

	/**
	 * Default constructor to build new Artisan_Email class.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C Configuration object.
	 * @retval Object Returns new Artisan_Email object.
	 */
	public function __construct(Artisan_Config &$C) { }
	
	/**
	 * Destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	public function __destruct() { }
	
	
}