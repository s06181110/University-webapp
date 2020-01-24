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
$q_query = $_POST['query'];
if (isset($q_query)) {
    echo "<h1>$q_query</h1><hr>";
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
        while (!feof($fp)) {
            $response .= fgets($fp, 4096);
        }
        fclose($fp);

        return explode("\r\n\r\n", $response, 2);
    }
    list($head, $data) = httpRequest($q_query);
    echo $data;
}

?>

</body>
</html>