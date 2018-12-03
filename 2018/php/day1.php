<?php
$InputFile = "../input/input-day1.txt";

$deltas = array_map("intval", explode("\n", trim(file_get_contents($InputFile))));

echo "Part 1: ".array_sum($deltas).PHP_EOL;

$frequency = 0;
$seen = [];

while(true) {
    foreach($deltas as $statement) {
        $frequency += $statement;
        if(isset($seen[$frequency])) {
            echo "Part 2: ".$frequency.PHP_EOL;
            break 2;
        }
        $seen[$frequency] = true;
    }
}
