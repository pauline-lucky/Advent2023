<?php

$file = fopen(__DIR__ . '/5.txt', 'r');

ini_set('memory_limit', '5G');

$seedRanges = trim(fgets($file));
fgets($file);
$seedRanges = explode(' ', $seedRanges);
array_shift($seedRanges);

function readDataFromFile($file)
{
    $header = fgets($file);
    $ranges = [];
    while ($line = fgets($file)) {
        $line = trim($line);
        if (empty($line)) {
            break;
        }
        $ranges[] = explode(' ', $line);
    }

    return $ranges;
}

function findRequiredNumberFromRange($source, $ranges)
{
    $source = (int) $source;
    foreach ($ranges as $range) {
        if ($source < $range[1] || $source >= $range[1] + $range[2]) {
            continue;
        }
        $diff = $source - $range[1];

        return $range[0] + $diff;
    }

    return $source;
}

$allDestinationRanges = [];
while ($destinationRanges = readDataFromFile($file)) {
    if (empty($destinationRanges)) {
        break;
    }
    $allDestinationRanges[] = $destinationRanges;
}

$min = 6000000000; // 6 000 000 000
for ($i = 0; $i < count($seedRanges); $i = $i + 2) {
    $rangeEnd = $seedRanges[$i] + $seedRanges[$i + 1];
    echo "Processing range from {$seedRanges[$i]} to {$rangeEnd} \n";
    ob_flush();
    flush();

    for ($seed = $seedRanges[$i]; $seed < $rangeEnd; $seed = $seed + 1) {
        $destination = $seed;
        foreach ($allDestinationRanges as $destinationRanges) {
            $destination = findRequiredNumberFromRange($destination, $destinationRanges);
        }
        if ($destination < $min) {
            $min = $destination;
        }
        // echo $seed . ' - ' . $destination . "\n";
    }

    echo "Current min - {$min} \n";
}

echo "\n Min min -" . $min . "\n";

fclose($file);
