<?php

$file = fopen(__DIR__ . '/4.txt', 'r');

$sum = 0;
$cardcopies = [];
while ($line = fgets($file)) {
    [$cardNum, $card] = explode(': ', $line);
    [$winning, $all]  = explode(' | ', $card);
    preg_match_all('/\s*(\d+)\s*/', $winning, $winning);
    $winning = $winning[1];
    preg_match_all('/\s*(\d+)\s*/', $all, $all);
    $all = $all[1];
    $all = array_flip($all);

    preg_match('/\s*(\d+)\s*/', $cardNum, $cardNum);
    $cardNum = $cardNum[1];
    $cardcopies[$cardNum] = ($cardcopies[$cardNum] ?? 0) + 1;

    $count = 0;
    foreach ($winning as $number) {
        if (isset($all[trim($number)])) {
            $count++;
            $cardcopies[$cardNum + $count] = ($cardcopies[$cardNum + $count] ?? 0) + $cardcopies[$cardNum];
        }
    }

    $sum = array_sum($cardcopies);
}

echo "\n" . $sum . "\n";

fclose($file);
