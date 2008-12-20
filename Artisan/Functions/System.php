<?php

/**
 * Returns the peak memory in MB during script execution.
 * @author vmc <vmc@leftnode.com>
 * @retval float The peak memory used during script execution in megabytes.
 */
function asfw_peak_memory() {
	$one_mb = 1024*1024;
	$memory = memory_get_peak_usage() / $one_mb;
	$memory = round($memory, 4);
	return $memory;
}
