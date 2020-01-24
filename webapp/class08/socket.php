<?php

function httpRequest($url) {
    $purl = parse_url($url);
    print_r($purl);
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
}

//$query = "http://www.kyoto-su.ac.jp:80/faculty/cse/index.html";
$query = "http://www.kyoto-su.ac.jp/";
httpRequest($query);
