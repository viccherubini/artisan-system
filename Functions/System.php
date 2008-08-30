<?php

function asfw_peak_memory() {
	$one_mb = 1024*1024;
	$memory = memory_get_peak_usage() / $one_mb;
	$memory = round($memory, 4) . 'MB';
	return $memory;
}

?>
