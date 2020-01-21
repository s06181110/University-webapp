<?php

echo '4-1, 4-2';
echo '<br><br>';
$week = array('月', '火', '水');
$month = array('Oct' => 10, 'Nov' => 11, 'Dec' => 12);

echo '今日は'.$month['Oct'].'月の'.$week[0].'曜です。';

echo '<br><br><br>';
echo '4-3';
echo '<br><br>';

$week = array('月', '火', '水', '木', '金', '土', '日');

$out = '1週間は、';

foreach ($week as $day) {
    $out .= $day.'、';
}

echo $out.'の'.count($week).'日間です。';

echo '<br><br><br>';
echo '4-4, 4-5';
echo '<br><br>';

?>
<form action="ensyu4-1.php" method="post">
    <label for="num">数値を入力</label>
    <input type="text" name="num" id="num" value="<?php $input?>">
    <input type="submit" value="送信">
</form>

<?php


if (isset($_POST['num'])) {
    echo 'ifで実装<br>';
    $input = $_POST['num'];
    if (3 <= $input && $input <= 5) {
        echo $input . '月は' . '春です';
    } elseif (6 <= $input && $input <= 8) {
        echo $input . '月は' . '夏です';
    } elseif (9 <= $input && $input <= 11) {
        echo $input . '月は' . '秋です';
    } elseif (12 == $input || 1 <= $input && $input <= 2) {
        echo $input . '月は' . '冬です';
    } else {
        echo '1から12月までの月を入力してください';
    }

    echo '<br><br>switchで実装<br>';
    switch ($input) {
        case 3:
        case 4:
        case 5:
            echo $input . '月は' . '春です';
            break;
        case 6:
        case 7:
        case 8:
            echo $input . '月は' . '夏です';
            break;
        case 9:
        case 10:
        case 11:
            echo $input . '月は' . '秋です';
            break;
        case 12:
        case 1:
        case 2:
            echo $input . '月は' . '冬です';
            break;
        default:
            echo '1から12月までの月を入力してください';
            break;
    }
}
