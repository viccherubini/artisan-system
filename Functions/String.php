<?php

/**
 * Returns the first letter of a string.
 */
function asfw_first_letter($word) {
	$w = NULL;
	$word = trim($word);
	if ( false === empty($word) ) {
		$w = $word[0];
	}
	return $w;
}

?>
