<?php

interface Artisan_Session_Interface {
	/**
	 * Open save handler.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function open();
	
	/**
	 * Close save handler.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function close();
	
	/**
	 * Read save handler.
	 * @author vmc <vmc@leftnode.com>
	 * @param $session_id The ID of the session from which to load data.
	 * @retval Mixed Returns the data from the session, can be of any datatype.
	 */
	public function read($session_id);
	
	/**
	 * Write save handler.
	 * @author vmc <vmc@leftnode.com>
	 * @param $session_id The ID of the session to write data.
	 * @param $session_data The data to write, PHP will serialize this data.
	 * @retval Boolean Returns true if data was written, false otherwise.
	 */
	public function write($session_id, $session_data);
	
	/**
	 * Destroy save handler.
	 * @author vmc <vmc@leftnode.com>
	 * @param $session_id The ID of the session for which to delete data.
	 * @retval boolean Returns true if data deleted, false otherwise.
	 */
	public function destroy($session_id);
	
	/**
	 * Garbage Collector save handler.
	 * @author vmc <vmc@leftnode.com>
	 * @param $life The number of seconds before decayed data is deleted.
	 * @retval boolean Returns true if data deleted, false otherwise.
	 */
	public function gc($life);
}
