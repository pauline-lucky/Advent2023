<?php

$file = fopen(__DIR__ . '/6.txt', 'r');
$line = fgets($file);
preg_match_all('/\s*(\d+)\s*/', $line, $time);
$time = $time[1];

$line = fgets($file);
preg_match_all('/\s*(\d+)\s*/', $line, $distance);
$distance = $distance[1];

$prod = 1;
for ($i = 0; $i < count($time); ++$i) {
    $d     = sqrt($time[$i] * $time[$i] - 4 * $distance[$i]);
    $x1    = ($time[$i] + $d) / 2;
    $x2    = ($time[$i] - $d) / 2;
    $count = floor($x1) - floor($x2);
    if ($count === $x1 - $x2) {
        $count--;
    }
    $prod *= $count;
}
echo "\n" . $prod . "\n";

fclose($file);
