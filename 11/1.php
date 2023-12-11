<?php

$file = fopen(__DIR__ . '/input.txt', 'r');

$map = [];
$galaxies = [];
$columns = [];
$row = 0;
while ($line = fgets($file)) {
    $map[$row] = str_split(trim($line));
    $col = strpos($line, '#');
    if (is_int($col)) {
        while(is_int($col)) {
            $columns[] = $col;
            $galaxies[] = [$row, $col];
            $col = strpos($line, '#', $col + 1);
        }
    } else {
        $map[$row+1] = $map[$row];
        $row++;
    }
    $row++;
}

$colsToduplicate = array_diff(array_keys($map[0]), $columns);
$colsToduplicate= array_reverse($colsToduplicate);
foreach ($colsToduplicate as $col) {
    foreach ($map as &$row) {
        for ($j = count($row); $j > $col; --$j) {
            $row[$j] = $row[$j-1];
        }
        $row[$col+1] = '.';
    }
}
unset($row);

$galaxies = [];
foreach ($map as $rowInd => $row) {
    $line = implode('', $row);
    $col = strpos($line, '#');
    if (is_int($col)) {
        while(is_int($col)) {
            $galaxies[] = [$rowInd, $col];
            $col = strpos($line, '#', $col + 1);
        }
    }
}

$sum = 0;
for ($i=0; $i < count($galaxies); ++$i) {
    for($j = $i+1; $j <count($galaxies); ++$j) {
        $path = abs($galaxies[$j][0] - $galaxies[$i][0]) + abs($galaxies[$j][1] - $galaxies[$i][1]);
        $sum += $path;
    }
}


echo "\n" . $sum . "\n";

fclose($file);
