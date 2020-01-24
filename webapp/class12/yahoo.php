<?php
function yahoo_mecab($data, $pos){
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

$pos = '名詞';
$data = "私は京都産業大学コンピュータ理工学部です。";
yahoo_mecab($data, $pos);