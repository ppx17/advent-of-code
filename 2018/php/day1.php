<?php
$InputFile = "../input/input-day1.txt";

$statements = explode("\n", trim(file_get_contents($InputFile)));

$sum = 0;
$history = [];
$part1 = $part2 = null;

while($part2 === null) {
    foreach($statements as $statement) {
        $sum += $statement;
        if($part2 === null && in_array($sum, $history)) {
            $part2 = $sum;
            break;
        }
        $history[] = $sum;
    }
    if($part1 === null) {
        $part1 = $sum;
    }
}

echo "Part 1: ".$part1.PHP_EOL;
echo "Part 2: ".$part2.PHP_EOL;
