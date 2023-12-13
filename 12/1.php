<?php

function check($string, $ind, $pattern, &$count)
{
    if ($ind >= strlen($string)) {
        if (preg_match("/^$pattern$/", $string, $matches)) {
            $count++;
        }
        return;
    }
    if ($string[$ind] !== '?') {
        check($string, $ind+1, $pattern, $count);
        return;
    }
    foreach (['.', '#'] as $replace) {
        $string[$ind] = $replace;
        check($string, $ind+1, $pattern, $count);
    }
}

$file = fopen(__DIR__ . '/input.txt', 'r');
$sum = 0;
while ($line = fgets($file)) {
    [$springs, $damagedGroups] = explode(' ', trim($line));
    $damagedGroups = explode(',', $damagedGroups);

    $pattern = [];
    foreach ($damagedGroups as $group) {
        $pattern[] = "#{{$group},{$group}}";
    }
    $pattern = '\.*' . implode('\.+', $pattern) . '\.*';

    $count = 0;
    check($springs, 0, $pattern, $count);
    $sum +=$count;
}

echo "\n" . $sum . "\n";

fclose($file);