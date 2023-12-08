<?php

$file = fopen(__DIR__ . '/3.txt', 'r');

$sum    = 0;
$matrix = [];
$i      = 0;
while ($line = fgets($file)) {
    $line = trim($line);
    // echo $line . "\n";
    for ($j = 0; $j < strlen($line); ++$j) {
        $matrix[$i][$j] = $line[$j];
    }
    ++$i;
}

function isAdjucent($matrix, $row, $col)
{
    $rowStart = $row - 1 < 0 ? 0 : $row - 1;
    $rowEnd   = $row + 1 >= count($matrix) ? $row : $row + 1;
    $colStart = $col - 1 < 0 ? 0 : $col - 1;
    $colEnd   = $col + 1 >= count($matrix[0]) ? $col : $col + 1;

    for ($i = $rowStart; $i <= $rowEnd; ++$i) {
        for ($j = $colStart; $j <= $colEnd; ++$j) {
            $item = $matrix[$i][$j];
            if (!is_numeric($item) && $item !== '.') {
                return [$i, $j];
            }
        }
    }

    return null;
}

$number     = '';
$isAdjacent = null;
$adjacentNumbers = [];
for ($i = 0; $i < count($matrix); ++$i) {
    if ($number && $isAdjacent) {
        $adjacentNumbers[$isAdjacent[0]][$isAdjacent[1]][] = $number;
        $number     = '';
        $isAdjacent = null;
    }
    for ($j = 0; $j < count($matrix[$i]); ++$j) {
        if (is_numeric($matrix[$i][$j])) {
            $number .= $matrix[$i][$j];
            if (!$isAdjacent) {
                $isAdjacent = isAdjucent($matrix, $i, $j);
            }
        } else {
            if ($number && $isAdjacent) {
                $adjacentNumbers[$isAdjacent[0]][$isAdjacent[1]][] = $number;
            }
            $number     = '';
            $isAdjacent = null;
        }
    }
}
if ($number && $isAdjacent) {
    $adjacentNumbers[$isAdjacent[0]][$isAdjacent[1]][] = $number;
}

foreach ($adjacentNumbers as $row) {
    foreach ($row as $numbers) {
        if (count($numbers) > 1) {
            $sum += $numbers[0] * $numbers[1];
        }
    }
}

echo "\n" . $sum . "\n";

fclose($file);
