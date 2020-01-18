<!-- g1744221 榎本啓希 -->

<?php
function arrayMin($anArray) {
    $min = $anArray[0];
    for ($index = 1; $index < sizeof($anArray); $index++) {
        if ($anArray[$index] < $min) {
            $min = $anArray[$index];
        }
    }
    return $min;
}

$anArray = [12, 5, 7, 20, 8];
echo arrayMin($anArray);

?>