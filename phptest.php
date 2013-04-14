<?php

$state = true;
while (TRUE) {
    usleep(3000);
    if ($state) {
        $packet = chr(84) . chr(84) . chr(84) . chr(84) . chr(84);
    } else {
        $packet = chr(171) . chr(171) . chr(171) . chr(171) . chr(171);
    }
    $fp = stream_socket_client("udp://192.168.1.5:1099", $errno, $errstr, STREAM_SERVER_BIND);
    fwrite($fp, chr(strlen($packet) + 1) . $packet);
    $state = !$state;
}

