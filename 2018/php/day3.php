<?php
$data = $data ?? file_get_contents("../input/input-".basename(__FILE__, '.php').".txt");

preg_match_all(
    "/#(?<id>[0-9]+) @ (?<x>[0-9]+),(?<y>[0-9]+): (?<w>[0-9]+)x(?<h>[0-9]+)/",
    $data,
    $claims,
    PREG_SET_ORDER);

$len = count($claims);
for($i = 0; $i < $len; $i++) {
    $claims[$i]['x'] = (int)$claims[$i]['x'];
    $claims[$i]['y'] = (int)$claims[$i]['y'];
    $claims[$i]['w'] = (int)$claims[$i]['w'];
    $claims[$i]['h'] = (int)$claims[$i]['h'];
}

$grid = [];

foreach ($claims as $claim) {
    $maxX = ($claim['x'] + $claim['w']);
    $maxY = ($claim['y'] + $claim['h']);
    $startY = $claim['y'];
    for ($x = $claim['x']; $x < $maxX; $x++) {
        for ($y = $startY; $y < $maxY; $y++) {
            $idx = $x * 1000 + $y;
            if(isset($grid[$idx])) {
                $grid[$idx] = true;
            }else{
                $grid[$idx] = false;
            }
        }
    }
}

$count = 0;

foreach ($grid as $value) {
    if ($value === true) {
        $count++;
    }
}

echo "Part 1: " . $count . PHP_EOL;

function hasOverlap(array $grid, $claim) {
    for ($x = $claim['x']; $x < ($claim['x'] + $claim['w']); $x++) {
        for ($y = $claim['y']; $y < ($claim['y'] + $claim['h']); $y++) {
            $idx = $x * 1000 + $y;
            if($grid[$idx] === true) { return true; }
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