<html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" >
    <title>単語出現回数</title>
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
function insert_sql($data) {
    try {
        $pdh = new PDO('sqlite:test.db');
        $sql = "insert into list (url, contents) values (?, ?)";
        $sth = $pdh->prepare($sql);
        $sth->execute($data);
    } Catch (PDOException $e) {
        print "error! " . $e->getMessage() . "<br>";
        die();
    }
}

function yahoo_mecab($data, $pos, $query_url)
{
    $api_key = "dj00aiZpPXRaUnBGQWVRNDFiMyZzPWNvbnN1bWVyc2VjcmV0Jng9MTU-";
    $query = urlencode($data);
    $res = "surface,feature";

    $api_url = "https://jlp.yahooapis.jp/MAService/V1/parse";
    $api_url .= "?appid=" . $api_key . "&response=" . $res . "&sentence=" . $query;

    $rss = file_get_contents($api_url);
    $xml = simplexml_load_string($rss);
    foreach ($xml->ma_result->word_list->word as $item) {
        if (mb_ereg($pos, $item->feature)) {
            $contents = $item->surface;
            insert_sql(array($query_url, $contents));
            $words[] = "$contents";
        }
    }
    return $words;
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
    $word = [];
    do {
        $data2 = mb_substr($data, $start-$end, $size);
        $tango = yahoo_mecab($data2, $pos, $query);
        $start += $size;
        foreach ($tango as $val) {
            $word["$val"] = $val;
        }
    } while (($start - $size) <= $end);
}

?>
</body>
</html>
