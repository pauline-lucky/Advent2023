<?php

$file = fopen(__DIR__ . '/8.txt', 'r');

$instruction = trim(fgets($file));
fgets($file);

$network = [];
while ($line = fgets($file)) {
    // echo $line . "\n";
    preg_match('/([A-Z]{3})\s=\s\(([A-Z]{3}),\s([A-Z]{3})\)/', $line, $matches);
    $network[$matches[1]] = ['L' => $matches[2], 'R' => $matches[3]];
}

$steps = 0;
$current = 'AAA';
while ($current !== 'ZZZ') {
    for ($i = 0; $i < strlen($instruction); ++$i) {
        $current = $network[$current][$instruction[$i]];
        $steps++;
        if ($current === 'ZZZ') {
            break;
        }
    }
}

echo "\n" . $steps . "\n";

fclose($file);
