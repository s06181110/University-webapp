<!-- g1744221 榎本啓希 -->

<?php
function sumFunc($n) {
    $sum = 0;
    for ($index = 1; $index <= $n; $index++) {
        $sum += $index;
    }
    return $sum;
}

echo sumFunc(10);

?>