<?php

	function strToBin($mystring){
		$mybitseq = "";
		$end = strlen($mystring);
		for($i = 0 ; $i < $end; $i++){
			$mybyte = decbin(ord($mystring[$i])); // convert char to bit string
			$mybitseq .= substr("00000000",0,8 - strlen($mybyte)) . $mybyte; // 8 bit packed
		}
		return $mybitseq;
	}

	$fp = stream_socket_client("udp://192.168.1.5:1099", $errno, $errstr, STREAM_SERVER_BIND);
	if (!$fp) {
		die("$errstr ($errno)");
	}
	
	// 50 - code for stat request; 
	// 10 - responce packet length; 
	
	
	fwrite($fp, chr(50));
	$a = fread($fp, 10);
	
	echo strToBin($a);
