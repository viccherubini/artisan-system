<?php

/**
 * This abstract class allows email to be sent several ways: through Sendmail/PHP
 * and through SMTP. Support for IMAP to come.
 * @author vmc <vmc@leftnode.com>
 * @todo Finish implementing this class! This will have a static factory() method to build the different adapters
 */
class Artisan_Email {
	public static function factory($adapter) {

	}
}
