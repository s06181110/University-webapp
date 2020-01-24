<?php

$hostname = "www.google.co.jp";
$fp = fsockopen($hostname, 80, $errno, $errstr);
socket_set_timeout($fp, 10);

$request = "GET /index.html HTTP/1.0\r\n\r\n";
fputs($fp, $request);

$response = "";
while(!feof($fp)) {
    $response .= fgets($fp, 4096);
}
fclose($fp);

echo $response;