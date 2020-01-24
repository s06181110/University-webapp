<?php

function httpRequest($url) {
    $purl = parse_url($url);
    $pscheme = $purl["scheme"];
    $phost = $purl["host"];
    $pport = $purl["port"];
    $ppath = $purl["path"];

    if (!isset($purl["port"])) $pport = 80;
    if (!isset($purl["path"])) $ppath = "/";

    echo "<br>";
    echo sprintf("プロトコル: %s<br>", $pscheme);
    echo sprintf("ホスト: %s<br>", $phost);
    echo sprintf("ポート: %s<br>", $pport);
    echo sprintf("パス: %s<br>", $ppath);

    $fp = fsockopen($phost, $pport, $errno, $errstr);
    socket_set_timeout($fp, 10);

    $request = "GET " . $ppath . " HTTP/1.0\r\n\r\n";
    fputs($fp, $request);

    $response = "";
    while(!feof($fp)) {
        $response .= fgets($fp, 4096);
    }
    fclose($fp);

    echo $response;
}

//$query = "http://www.kyoto-su.ac.jp:80/faculty/cse/index.html";
$query = "http://www.kyoto-su.ac.jp/";
httpRequest($query);
