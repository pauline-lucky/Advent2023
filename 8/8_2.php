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

$steps = 0;
$end = false;
while (!$end) {
    for ($i = 0; $i < strlen($instruction); ++$i) {
        $end = true;
        foreach ($states as &$state) {
            $state = $network[$state][$instruction[$i]];
            if (!str_ends_with($state, 'Z')) {
                $end = false;
            }
        }
        $steps++;
        if ($end) {
            break;
        }
    }
    echo "steps: $steps; states: " . implode(' ', $states) . "\n";
}

echo "\nsteps: " . $steps . "\n";

fclose($file);
