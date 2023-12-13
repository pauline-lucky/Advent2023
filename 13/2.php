<?php

$file = fopen(__DIR__ . '/input.txt', 'r');

function findReflection($pattern) {
    $ref = [];
    for ($i = 1; $i < count($pattern); ++$i) {
        if($pattern[$i] == $pattern[$i-1] || levenshtein($pattern[$i], $pattern[$i-1]) == 1) {
            $ref[] = $i;
        }
    }

    foreach($ref as $r) {
        $flag = true;
        $hasReplaced = false;
        for ($i = 0; $r+$i < count($pattern) && $r-$i-1>=0; ++$i) {
            if ($pattern[$r+$i] !== $pattern[$r-$i-1]) {
                if (!$hasReplaced && levenshtein($pattern[$r+$i], $pattern[$r-$i-1]) == 1) {
                    $hasReplaced = true;
                } else {
                    $flag = false;
                    break;
                }
            }
        }
        if ($flag && $hasReplaced) {
            return $r;
        }
    }
    return 0;
}

$pattern = [];
$pattern2 = [];
$row = $col = $mirrow = $mircol = 0;
$sum = 0;
while ($line = fgets($file)) {
//    echo ($line) ."\n";
    $line = trim($line);
    if ($line === '') {
        $mirrow = findReflection($pattern);
        $mircol = findReflection($pattern2);
        $sum+=$mirrow*100+$mircol;
        echo "$mirrow $mircol $sum\n";
        $pattern = [];
        $pattern2 = [];
        $row = $col = 0;
        continue;
    }
    $pattern[$row] = $line;
    for ($i = 0; $i < strlen($line); ++$i) {
        $pattern2[$i] = ($pattern2[$i] ?? '') . $line[$i];
    }
    $row++;
    $col++;
}

$mirrow = findReflection($pattern);
$mircol = findReflection($pattern2);
$sum+=$mirrow*100+$mircol;




echo "\n" . $sum . "\n";

fclose($file);
