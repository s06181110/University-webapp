<!--
名前：榎本 啓希
学籍番号: 744221
進捗: 6番
更新日: 1/25 16:10
入力URL:
    http://aokilab.kyoto-su.ac.jp/index-j.html
    http://www.kyoto-su.ac.jp/faculty/cse/index.html
    https://www.kyoto-su.ac.jp/entrance/index-ksu.html
-->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" >
    <title>最終課題</title>
</head>
<body>
<h1>実践Webアプリケーション - 最終課題 -</h1>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <p>
        <input type="text" name="query" placeholder="解析するURL">
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
// 入力されたURLは以前検索されているか調べる
function exist_query_data($query_url) {
    $count = 0;
    try {
        $dbh = new PDO('sqlite:final.db');
        $sql = "select count(*) from list where url like ?";
        $sth = $dbh->prepare($sql);
        $sth->execute([$query_url]);
        $count = $sth->fetchColumn();
    } Catch (PDOException $e) {
        print "error! " . $e->getMessage() . "<br>";
        die();
    }
    return $count > 0;
}
function insert_sql($data) {
    try {
        $dbh = new PDO('sqlite:final.db');
        $sql = "insert into list (url, contents) values (?, ?)";
        $sth = $dbh->prepare($sql);
        $sth->execute($data);
    } Catch (PDOException $e) {
        print "error! " . $e->getMessage() . "<br>";
        die();
    }
}
function count_sql_data($words, $query_url) {
    $list_word = [];
    try {
        $dbh = new PDO("sqlite:final.db", '', '');
        // 検索したサイトでの出現単語総数
        $sql = "select count(*) from list where url like ?";
        $sth = $dbh->prepare($sql);
        $sth->execute([$query_url]);
        $total = $sth->fetchColumn();
        // ドキュメント総数
        $sql = "select count(distinct url) from list";
        $sth = $dbh->prepare($sql);
        $sth->execute();
        $document_all = $sth->fetchColumn();
        // 単語のカウント
        foreach ($words as $keyword => $value) {
            // 検索したサイトでの単語出現回数
            $sql = "select count(url) from list where contents like ? and url like ?";
            $sth = $dbh->prepare($sql);
            $sth->execute([$keyword, $query_url]);
            $cnt = $sth->fetchColumn();
            $tf = $cnt/$total; // tf = (ドキュメント内での)単語出現回数 / 単語出現総数
            // 各ドキュメントに出現するか
            $sql = "select count(distinct url) from list where contents like ?";
            $sth = $dbh->prepare($sql);
            $sth->execute([$keyword]);
            $document_num = $sth->fetchColumn();
            $df = $document_num/$document_all; // 任意の単語が出現するドキュメント数/総ドキュメント数
            $idf = log($document_all/$document_num);
            $tf_idf = $tf * $idf;
            $list_word["$keyword"] = array('count' => $cnt, 'tf' => $tf, 'df' => $df, 'idf' => $idf, 'tf-idf' => $tf_idf);
        }
    } catch (PDOException $e) {
        print "エラー!: " . $e->getMessage() . "<br/>";
        die();
    }
    return $list_word;
}
function yahoo_mecab($data, $pos, $query_url, $is_insert)
{
    $api_key = "dj00aiZpPXRaUnBGQWVRNDFiMyZzPWNvbnN1bWVyc2VjcmV0Jng9MTU-";
    $query = urlencode($data);
    $res = "surface,feature";

    $api_url = "https://jlp.yahooapis.jp/MAService/V1/parse"; // apiのurl
    $api_url .= "?appid=" . $api_key . "&response=" . $res . "&sentence=" . $query; // 各種queryを乗っける

    $rss = file_get_contents($api_url);
    $xml = simplexml_load_string($rss);

    $is_exist = exist_query_data($query_url);
    foreach ($xml->ma_result->word_list->word as $item) {
        if (mb_ereg($pos, $item->feature)) {
            $contents = $item->surface;
            if($is_insert) insert_sql(array($query_url, $contents)); //検索したことない場合SQLに入れる
            $words[] = "$contents";
        }
    }
    return $words;
}
$query = $_POST['query'];
if (isset($query)) {
    echo "<h1>$query</h1><hr>";
    list($head, $data) = httpRequest($query);
    $pos = '名詞';
    $data = strip_tags($data);
    $start = 0;
    $size = 512;
    $end = mb_strlen($data);
    $words = [];
    $is_exist = exist_query_data($query);
    do {
        $split_data = mb_substr($data, $start-$end, $size);
        $mecab_result = yahoo_mecab($split_data, $pos, $query, !$is_exist);
        $start += $size;
        foreach ($mecab_result as $val) {
            $words["$val"] = $val;
        }
    } while (($start - $size) <= $end);
    $list_word = count_sql_data($words, $query);
    print_top10($list_word);
}

function print_top10 ($list_word) {
//    arsort($list_word);
    // tf-idfでソートする
    foreach ((array) $list_word as $key => $value) {
        $sort[$key] = $value['tf-idf'];
    }
    array_multisort($sort, SORT_DESC, $list_word);
    $rank = 1;
    echo "<table border='1' style='border-collapse: collapse' width='80%'>
            <tr align='center'>
                <th>順位</th>
                <th>単語</th>
                <th>出現回数</th>
                <th>tf値</th>
                <th>df値</th>
                <th>idf値</th>
                <th>tf-idf値</th>
            </tr>";
    foreach ($list_word as $key => $value) {
        if ($rank == 6) break;
        $count = $value["count"];
        $tf = $value['tf'];
        $df = $value['df'];
        $idf = $value['idf'];
        $tf_idf = $value['tf-idf'];
        echo "<tr align='center'>
                <td>$rank</td>
                <td>$key</td>
                <td>$count</td>
                <td>$tf</td>
                <td>$df</td>
                <td>$idf</td>
                <td>$tf_idf</td>
              </tr>";
        $rank++;
    }
    echo "</table>";
}

?>
</body>
</html>
