<?php

/**
 * fl stands for First Letter, to return 
 * the first letter of a word.
*/
function artisan_first_letter($word) {
	$w = NULL;
	$word = trim($word);
	if ( false === empty($word) ) {
		$w = $word[0];
	}
	return $w;
}

?>
