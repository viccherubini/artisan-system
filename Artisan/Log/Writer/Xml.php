<?php

/**
 * @see Artisan_Log_Writer
 */
require_once 'Artisan/Log/Writer.php';

/**
 * @see Artisan_Log_Writer_Filesystem
 */
require_once 'Artisan/Log/Writer/Filesystem.php';

/**
 * Writes a log array to an XML file.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Log_Writer_Xml extends Artisan_Log_Writer_Filesystem {
	/**
	 * Write the log data out to the specified directory.
	 * @author vmc <vmc@leftnode.com>
	 * @param $log Reference to the log array to print.
	 * @retval boolean Returns true.
	 * @todo Finish implementing this!
	 */
	public function flush(&$log) {
		return true;
	}
}
