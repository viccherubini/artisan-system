<?php

require_once 'Artisan/Email/Adapter.php';

class Artisan_Mail_Adapter_Mail extends Artisan_Adapter_Email {
	public function send() {
		$this->_check();
		
		$to_list = NULL;
		$len = count($this->_toEmailList);
		for ( $i=0; $i<$len; $i++ ) {
			if ( false === empty($this->_toNameList[$i]) ) {
				$to_list .= '<' . $this->_toNameList[$i] . '> ';
			}
			
			$to_list .= $this->_toEmailList[$i];
			if ( $i < ($len-1) ) {
				$to_list .= ', ';
			}
		}
	}
}