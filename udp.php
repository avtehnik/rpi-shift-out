<?php



$fp = stream_socket_client("udp://127.0.0.1:1099", $errno, $errstr, STREAM_SERVER_BIND);
if (!$fp) {
    die("$errstr ($errno)");
}


 $packet = chr(85).chr(100).chr(85);
	

fwrite($fp,chr(strlen($packet)).$packet );

?>
