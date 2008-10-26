<?php

interface Artisan_Session_Interface {
	public function open();
	public function close();
	public function read($session_id);
	public function write($session_id, $session_data);
	public function destroy($session_id);
	public function gc($life);
}
