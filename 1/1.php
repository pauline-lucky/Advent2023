<?php

$file = fopen(__DIR__ . '/1.txt', 'r');

$sum     = 0;
$count   = 0;
$numbers = [
    '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9,
    'zero' => 0, 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9,
];
while ($line = fgets($file)) {
    $count++;
    $len = strlen($line);
    for ($i = 0; $i < $len; ++$i) {
        foreach($numbers as $key => $number) {
            $temp = substr($line, $i);
            if (empty($first) && str_starts_with($temp, $key)) {
                $first = $number;
            }

            $temp = substr($line, 0, $len-$i);
            if (empty($last) && str_ends_with($temp, $key)) {
                $last = $number;
            }

            if (isset($first) && isset($last)) {
                break;
            }
        }
        if (isset($first) && isset($last)) {
            $number = $first . $last;
            echo "\n" . $number;
            $sum   += (int) $number;
            $first = $last = null;
            break;
        }
    }
}

echo "\n" . $sum;
echo "\n" . $count;

fclose($file);
