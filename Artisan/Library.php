<?php

function exs($k, $a) {
	if ( true === is_object($a) && true === isset($a->$k) ) {
		return true;
	}
	
	if ( true === is_array($a) && true === isset($a[$k]) ) {
		return true;
	}
	
	return false;
}

function er($k, $a, $return = NULL) {
	if ( true === is_object($a) && true === isset($a->$k) ) {
		return $a->$k;
	}
	
	if ( true === is_array($a) && true === isset($a[$k]) ) {
		return $a[$k];
	}
	
	return $return;
}