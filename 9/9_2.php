<?php

$file = fopen(__DIR__ . '/input.txt', 'r');

$sum = 0;
while ($line = fgets($file)) {
    $sequences = [
        explode(' ', trim($line))
    ];
    $end = false;
    $ind = 0;
    while (!$end) {
        $end = true;
        $sequence = [];
        for ($i = 1; $i < count($sequences[$ind]); ++$i) {
            $diff = $sequences[$ind][$i] - $sequences[$ind][$i-1];
            $sequence[] = $diff;
            if ($diff !== 0) {
                $end = false;
            }
        }
        $ind++;
        $sequences[$ind] = $sequence;
    }
    $prev = 0;
    for ($i = count($sequences) - 2; $i >=0; --$i) {
        $prev = $sequences[$i][0] - $prev;
    }
    $sum += $prev;
}

echo "\n" . $sum . "\n";

fclose($file);
