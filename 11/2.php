<?php

$file = fopen(__DIR__ . '/input.txt', 'r');

$map = [];
$galaxies = [];
$columns = [];
$row = 0;
$rowsToDuplicate = [];
while ($line = fgets($file)) {
    $map[$row] = str_split(trim($line));
    $col = strpos($line, '#');
    $rowsToDuplicate[$row] = is_int($col) ? 0 : 1;
    if (is_int($col)) {
        while(is_int($col)) {
            $columns[] = $col;
            $galaxies[] = [$row, $col];
            $col = strpos($line, '#', $col + 1);
        }
    }
    $row++;
}

$colsToduplicate = array_fill(0, count($map[0]), 1);
foreach ($columns as $col) {
    $colsToduplicate[$col] = 0;
}

foreach ($galaxies as &$coords) {
    $rows = array_slice($rowsToDuplicate, 0, $coords[0]);
    $cols = array_slice($colsToduplicate, 0, $coords[1]);
    $coords[0] += array_sum($rows) * 999999;
    $coords[1] += array_sum($cols) * 999999;
}

//var_dump($galaxies);
$sum = 0;
for ($i=0; $i < count($galaxies); ++$i) {
    for($j = $i+1; $j <count($galaxies); ++$j) {
        $path = abs($galaxies[$j][0] - $galaxies[$i][0]) + abs($galaxies[$j][1] - $galaxies[$i][1]);
        $sum += $path;
    }
}


echo "\n" . $sum . "\n";

fclose($file);
