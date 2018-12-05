<?php
$InputFile = "../input/input-day5.txt";

$units = trim(file_get_contents($InputFile));

function react_str_replace(string $units, ?string $exclude = null): int
{
    $searches = [];
    for ($charCode = ord('a'); $charCode <= ord('z'); $charCode++) {
        $letter = chr($charCode);
        if($letter === $exclude) {
            $searches[] = $letter;
            $searches[] = strtoupper($letter);
        }else{
            $searches[] = $letter.strtoupper($letter);
            $searches[] = strtoupper($letter).$letter;
        }
    }

    do {
        $units = str_replace($searches, '', $units, $changes);
    }while($changes > 0);
    return strlen($units);
}

echo "Part 1: " . react_str_replace($units) . PHP_EOL;


$smallest = strlen($units);

$sum = 0;
$count = 0;

for ($x = ord('a'); $x <= ord('z'); $x++) {
    $letter = chr($x);

    $reacted = react_str_replace($units, $letter);

    $sum += $reacted;
    $count++;

    if ($reacted < $smallest) {
        $smallest = $reacted;
    }

    if($reacted <  ($sum / $count) * 0.8) {
        // This one is more than 20% smaller, which qualifies as 'significantly' as stated in the assignment, so we cancel out here.
        break;
    }
}

echo "Part 2: " . $smallest . PHP_EOL;