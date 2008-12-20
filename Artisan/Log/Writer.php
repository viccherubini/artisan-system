<?php


abstract class Artisan_Log_Writer {
	abstract public function flush(&$log);
}