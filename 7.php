<?php

$file = fopen(__DIR__ . '/7.txt', 'r');

function getHandType($hand)
{
    $cardTypes = [];
    for ($i = 0; $i < 5; ++$i) {
        $cardTypes[$hand[$i]] = isset($cardTypes[$hand[$i]]) ? $cardTypes[$hand[$i]] + 1 : 1;
    }

    rsort($cardTypes);

    return match ($cardTypes[0]) {
        5 => 7,
        4 => 6,
        3 => $cardTypes[1] === 2 ? 5 : 4,
        2 => $cardTypes[1] === 2 ? 3 : 2,
        default => 1,
    };
}

function compareHands($a, $b) {
    $compareHandType = $a['type'] <=> $b['type'];
    if ($compareHandType !== 0) {
        return $compareHandType;
    }

    $cardRank = [
        'A' => 12, 'K' => 11, 'Q' => 10, 'J' => 9, 'T' => 8,
        '9' => 7, '8' => 6, '7' => 5, '6' => 4, '5' => 3, '4' => 2, '3' => 1, '2' => 0,
    ];
    for ($i = 0; $i < 5; ++$i) {
        $compareCardType = $cardRank[$a['hand'][$i]] <=> $cardRank[$b['hand'][$i]];
        if ($compareCardType !== 0) {
            return $compareCardType;
        }
    }
}

$hands = [];
while ($line = fgets($file)) {
    [$hand, $bid] = explode(' ', $line);
    $hands[] = [
        'hand' => $hand,
        'bid'  => $bid,
        'type' => getHandType($hand),
    ];
}

usort($hands, 'compareHands');

$sum = 0;
$rank = 1;
foreach ($hands as $hand) {
    $sum += $rank * $hand['bid'];
    ++$rank;
}


echo "\n" . $sum . "\n";

fclose($file);
