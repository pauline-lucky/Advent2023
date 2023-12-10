<?php

$file = fopen(__DIR__ . '/input.txt', 'r');

$sum = 0;
$map = [];
$wheresthenest = [];
$row = 0;
$srow = false;
$scol = false;
while ($line = fgets($file)) {
    $map[$row] = str_split(trim($line));
    $wheresthenest[$row] = array_fill(0, count($map[$row]), 0);
    $findS = array_search('S', $map[$row]);
    if (is_int($findS)) {
        $scol = $findS;
        $srow = $row;
        $wheresthenest[$srow][$scol] = 'S';
    }
    $row++;
}

$tiles = [
    '|' => [[1, 0], [-1, 0]],
    '-' => [[0, 1], [0, -1]],
    'L' => [[0, 1], [-1, 0]],
    'J' => [[0, -1], [-1, 0]],
    '7' => [[1, 0], [0, -1]],
    'F' => [[1, 0], [0, 1]],
];
$currents = [];
foreach ([[$srow, $scol + 1], [$srow, $scol - 1], [$srow + 1, $scol], [$srow - 1, $scol]] as $possible) {
    if (!isset($map[$possible[0]][$possible[1]])) {
        continue;
    }
    if (!isset($tiles[$map[$possible[0]][$possible[1]]])) {
        continue;
    }
    foreach ($tiles[$map[$possible[0]][$possible[1]]] as $step) {
        if ([$possible[0] + $step[0], $possible[1] + $step[1]] == [$srow, $scol]) {
            $currents[] = $possible;
            $wheresthenest[$possible[0]][$possible[1]] = $map[$possible[0]][$possible[1]];
            break;
        }
    }
}
$prevs = [[$srow, $scol], [$srow, $scol]];
$end = false;
$steps = 1;
while (!$end) {
    foreach ($currents as $ind => &$current) {
        $tile = $map[$current[0]][$current[1]];
        foreach ($tiles[$tile] as $step) {
            if ([$current[0] + $step[0], $current[1] + $step[1]] != $prevs[$ind]) {
                $prevs[$ind] = $current;
                $current[0] += $step[0];
                $current[1] += $step[1];
                $wheresthenest[$current[0]][$current[1]] = $map[$current[0]][$current[1]];
                break;
            }
        }
    }
    $steps++;
    if ($currents[0] == $currents[1]) {
        $end = true;
    }
}

$encl = 0;
foreach ($wheresthenest as $row) {
    unset($left);
    for ($i = 0; $i < count($row); ++$i) {
        if (in_array($row[$i], ['F', 'L'])) {
            for ($j = $i + 1; $j < count($row); ++$j) {
                if (in_array($row[$j], ['7', 'J', 'S'])) {
                    if (isset($left)) {
                        $encl += $i - $left - 1;
                        unset($left);
                        if (in_array($row[$i] . $row[$j], ['LJ', 'F7', 'FS'])) {
                            $left = $j;
                        }
                    } else {
                        if (!in_array($row[$i] . $row[$j], ['LJ', 'F7', 'FS'])) {
                            $left = $j;
                        }
                    }
                    $i = $j;
                    break;
                }
            }
        } elseif (in_array($row[$i], ['|'])) {
            if (isset($left)) {
                $encl += $i - $left - 1;
                unset($left);
            } else {
                $left = $i;
            }
        }
    }
}

echo "\n" . $encl . "\n";

fclose($file);
