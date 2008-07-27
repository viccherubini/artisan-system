<?php

function pprint_r($array, $return = false) {
	$str = '<pre>' . print_r($array, true) . '</pre>';
	if ( true === $return ) {
		return $str;
	} else {
		echo $str;
	}
}

?>