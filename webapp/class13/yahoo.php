<html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" >
    <title>form01</title>
</head>
<body>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <p>
        <input type="text" name="query">
        <input type="submit" value="送信">
    </p>
</form>
<?php
function httpRequest($url){
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
    while (!feof($fp)) {
        $response .= fgets($fp, 4096);
    }
    fclose($fp);

    return explode("\r\n\r\n", $response, 2);
}
function yahoo_mecab($data, $pos)
{
    $api_key = "dj00aiZpPXRaUnBGQWVRNDFiMyZzPWNvbnN1bWVyc2VjcmV0Jng9MTU-";
    $query = urlencode($data);
    $res = "surface,reading,pos,feature";

    $url = "https://jlp.yahooapis.jp/MAService/V1/parse";
    $url .= "?appid=" . $api_key . "&response=" . $res . "&sentence=" . $query;

    $rss = file_get_contents($url);
    $xml = simplexml_load_string($rss);

    foreach ($xml->ma_result->word_list->word as $item) {
        if (mb_ereg($pos, $item->feature)) {
            echo $item->surface . "|" . $item->feature;
            echo "<br>";
        }
    }
}
$query = $_POST['query'];
if (isset($query)) {
    echo "<h1>$q_query</h1><hr>";
    list($head, $data) = httpRequest($query);
    $pos = '名詞';
    $data = strip_tags($data);
    $start = 0;
    $size = 512;
    $end = mb_strlen($data);
    do {
        $data2 = mb_substr($data, $start-$end, $size);
        yahoo_mecab($data2, $pos);
        $start += $size;
    } while (($start - $size) <= $end);
}

?>
</body>
</html>
