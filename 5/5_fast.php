<?php

$file = fopen(__DIR__ . '/5.txt', 'r');

ini_set('memory_limit', '5G');

$seedRanges = trim(fgets($file));
fgets($file);
$seedRanges = explode(' ', $seedRanges);
array_shift($seedRanges);

$seedMap = [];
for ($i = 0; $i < count($seedRanges); $i = $i + 2) {
    $seedMap[] = [
        'source' => [
            'start' => $seedRanges[$i],
            'end'   => $seedRanges[$i] + $seedRanges[$i + 1] - 1,
        ],
        'destination' => [
            'start' => $seedRanges[$i],
            'end'   => $seedRanges[$i] + $seedRanges[$i + 1] - 1,
            'level' => 0,
        ],
    ];
}

function readDataFromFile($file)
{
    $header = fgets($file);
    // echo $header . "\n";
    $ranges = [];
    while ($line = fgets($file)) {
        $line = trim($line);
        if (empty($line)) {
            break;
        }
        $range    = explode(' ', $line);
        $ranges[] = [
            'source'      => [
                'start' => $range[1],
                'end'   => $range[1] + $range[2] - 1,
            ],
            'destination' => [
                'start' => $range[0],
                'end'   => $range[0] + $range[2] - 1,
            ],
        ];
    }

    // var_dump($ranges);
    return $ranges;
}

function getIntersection($seedRange, $range, $level)
{
    $newSeedRanges = [];
    if ($seedRange['destination']['start'] < $range['source']['start']) {
        $length                            = $range['source']['start'] - $seedRange['destination']['start'];
        $newSeedRanges[]                   = [
            'source'      => [
                'start' => $seedRange['source']['start'],
                'end'   => $seedRange['source']['start'] + $length - 1,
            ],
            'destination' => [
                'start' => $seedRange['destination']['start'],
                'end'   => $seedRange['destination']['start'] + $length - 1,
                'level' => $seedRange['destination']['level'],
            ],
        ];
        $seedRange['source']['start']      += $length;
        $seedRange['destination']['start'] += $length;
    }

    if ($seedRange['destination']['end'] > $range['source']['end']) {
        $length                          = $seedRange['destination']['end'] - $range['source']['end'];
        $newSeedRanges[]                 = [
            'source'      => [
                'start' => $seedRange['source']['end'] - $length + 1,
                'end'   => $seedRange['source']['end'],
            ],
            'destination' => [
                'start' => $seedRange['destination']['end'] - $length + 1,
                'end'   => $seedRange['destination']['end'],
                'level' => $seedRange['destination']['level'],
            ],
        ];
        $seedRange['source']['end']      -= $length;
        $seedRange['destination']['end'] -= $length;
    }

    if (
        $seedRange['destination']['start'] >= $range['source']['start']
        && $seedRange['destination']['end']
        <= $range['source']['end']
    ) {
        $shift                             = $range['destination']['start'] - $range['source']['start'];
        $seedRange['destination']['start'] += $shift;
        $seedRange['destination']['end']   += $shift;
        $seedRange['destination']['level']  = $level;
        $newSeedRanges[]                   = $seedRange;
    }

    return $newSeedRanges;
}

function isIntersecting($seedRange, $range, $level)
{
    if (
        $seedRange['destination']['start'] > $range['source']['end']
        || $seedRange['destination']['end'] < $range['source']['start']
        || $seedRange['destination']['level'] === $level
    ) {
        return false;
    }

    return true;
}

$level=1;
while ($destinationRanges = readDataFromFile($file)) {
    if (empty($destinationRanges)) {
        break;
    }
    $newSeedMap = [];
    foreach ($seedMap as $originalSeedRange) {
        $currentRanges = [$originalSeedRange];
        foreach ($destinationRanges as $range) {
            $newCurrentRanges = [];
            foreach ($currentRanges as $index => $seedRange) {
                if (isIntersecting($seedRange, $range, $level)) {
                    $newCurrentRanges = array_merge($newCurrentRanges, getIntersection($seedRange, $range, $level));
                } else {
                    array_push($newCurrentRanges, $seedRange);
                }
            }
            $currentRanges = $newCurrentRanges;
        }
        $newSeedMap = array_merge($newSeedMap, $currentRanges);
    }
    $seedMap = $newSeedMap;
    $level++;
}

// var_dump($seedMap);
$min = 6000000000; // 6 000 000 000
foreach ($seedMap as $seedRange) {
    if ($seedRange['destination']['start'] < $min) {
        $min = $seedRange['destination']['start'];
    }
}

echo "\n Min min - " . $min . "\n";

fclose($file);
