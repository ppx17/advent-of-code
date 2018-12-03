<?php
$InputFile = "../input/input-day3.txt";

preg_match_all(
    "/#(?<id>[0-9]+) @ (?<x>[0-9]+),(?<y>[0-9]+): (?<w>[0-9]+)x(?<h>[0-9]+)/",
    file_get_contents($InputFile),
    $claims,
    PREG_SET_ORDER);


$grid = [];

foreach ($claims as $claim) {
    for ($x = $claim['x']; $x < ($claim['x'] + $claim['w']); $x++) {
        for ($y = $claim['y']; $y < ($claim['y'] + $claim['h']); $y++) {
            $idx = $x * 1000 + $y;
            isset($grid[$idx]) ? ($grid[$idx] < 2 ? $grid[$idx]++ : null) : $grid[$idx] = 1;
        }
    }
}

$count = 0;
foreach ($grid as $value) {
    if ($value === 2) {
        $count++;
    }
}
echo "Part 1: " . $count . PHP_EOL;

function hasOverlap(array $grid, $claim) {
    for ($x = $claim['x']; $x < ($claim['x'] + $claim['w']); $x++) {
        for ($y = $claim['y']; $y < ($claim['y'] + $claim['h']); $y++) {
            $idx = $x * 1000 + $y;
            if($grid[$idx] === 2) { return true; }
        }
    }
    return false;
}

foreach($claims as $claim) {
    if( ! hasOverlap($grid, $claim)) {
        echo "Part 2: " . $claim['id'] . PHP_EOL;
        break;
    }
}