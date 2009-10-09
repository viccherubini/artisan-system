<?php

/**
 * An abstract writer class that must be extended to properly build a writer. A log
 * writer is a class that specifies where log data should be written.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Log_Writer {
	/**
	 * The method to flush the log data.
	 * @author vmc <vmc@leftnode.com>
	 * @param $log A reference to the log data.
	 * @retval boolean Returns true.
	 */
	abstract public function flush($log);
}