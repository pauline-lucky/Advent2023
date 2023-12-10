<?php

$file = fopen(__DIR__ . '/input.txt', 'r');

$sum = 0;
$map = [];
$row = 0;
$srow = false;
$scol = false;
while ($line = fgets($file)) {
    $map[$row] = str_split(trim($line));
    $findS = array_search('S', $map[$row]);
    if (is_int($findS)) {
        $scol = $findS;
        $srow = $row;
    }
    $row++;
}

$tiles = [
    '|' => [[1,0],[-1,0]],
    '-'=>[[0,1],[0,-1]],
    'L'=>[[0,1],[-1,0]],
    'J'=>[[0,-1],[-1,0]],
    '7'=>[[1,0],[0,-1]],
    'F'=>[[1,0],[0,1]],
];
$currents = [];
foreach([[$srow, $scol+1],[$srow, $scol-1],[$srow+1, $scol],[$srow-1, $scol]] as $possible) {
    if(!isset($map[$possible[0]][$possible[1]])) {
        continue;
    }
    if (!isset($tiles[$map[$possible[0]][$possible[1]]])) {
        continue;
    }
    foreach ($tiles[$map[$possible[0]][$possible[1]]] as $step) {
        if ([$possible[0] + $step[0], $possible[1] + $step[1]] == [$srow, $scol]) {
            $currents[] = $possible;
            break;
        }
    }
}
$prevs = [[$srow, $scol],[$srow, $scol]];
$end = false;
$steps = 1;
while(!$end) {
    foreach($currents as $ind => &$current) {
        $tile = $map[$current[0]][$current[1]];
        foreach ($tiles[$tile] as $step) {
            if ([$current[0] + $step[0], $current[1] + $step[1]] != $prevs[$ind]) {
                $prevs[$ind] = $current;
                $current[0] += $step[0];
                $current[1] += $step[1];
                break;
            }
        }
    }
    $steps++;
    if ($currents[0] == $currents[1]) {
        $end = true;
    }
}

echo "\n" . $steps . "\n";

fclose($file);
