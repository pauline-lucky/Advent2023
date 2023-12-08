<?php

$file = fopen(__DIR__ . '/2.txt', 'r');

$sum = 0;
while ($line = fgets($file)) {
    // echo $line . "\n";
    [$gameNum, $games] = explode(': ', $line);
    $games    = explode('; ', $games);
    $mincubes = ['red' => 1, 'green' => 1, 'blue' => 1];
    foreach ($games as $game) {
        // echo $game . "\n";
        $colors = explode(', ', $game);
        foreach ($colors as $color) {
            // echo $color . "\n";
            [$num, $col] = explode(' ', $color);
            // echo $num . $col . "\n";
            if ($num > $mincubes[trim($col)]) {
                $mincubes[trim($col)] = $num;
            }
        }
    }
    var_dump($mincubes);
    $power = $mincubes['red'] * $mincubes['green'] * $mincubes['blue'];
    $sum   += $power;
}

echo "\n" . $sum . "\n";

fclose($file);
