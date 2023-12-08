<?php

$file = fopen(__DIR__ . '/8.txt', 'r');

$instruction = trim(fgets($file));
fgets($file);

$network = [];
$states  = [];
while ($line = fgets($file)) {
    if (!preg_match('/([0-9A-Z]{3})\s=\s\(([0-9A-Z]{3}),\s([0-9A-Z]{3})\)/', $line, $matches)) {
        break;
    }
    $network[$matches[1]] = ['L' => $matches[2], 'R' => $matches[3]];
    if (str_ends_with($matches[1], 'A')) {
        $states[] = $matches[1];
    }
}

$iterations = [];
foreach ($states as $index => &$state) {
    $end = false;
    $stateIterations = 0;
    while (!$end) {
        for ($i = 0; $i < strlen($instruction); ++$i) {
            $state = $network[$state][$instruction[$i]];
            if (str_ends_with($state, 'Z')) {
                $end = true;
                break;
            }
        }
        $stateIterations++;
    }
    $iterations[$index] = $stateIterations;
}

$total = array_product($iterations) * strlen($instruction);

 echo "\nsteps: " . $total . "\n";

fclose($file);
