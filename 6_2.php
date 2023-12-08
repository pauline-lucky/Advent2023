<?php

$file = fopen(__DIR__ . '/6.txt', 'r');
$line = fgets($file);
$time = explode(':', $line)[1];
$time = str_replace(' ', '', $time);

$line     = fgets($file);
$distance = explode(':', $line)[1];
$distance = str_replace(' ', '', $distance);

$d     = sqrt($time * $time - 4 * $distance);
$x1    = ($time + $d) / 2;
$x2    = ($time - $d) / 2;
$count = floor($x1) - floor($x2);
if ($count === $x1 - $x2) {
    $count--;
}

echo "\n" . $count . "\n";

fclose($file);
