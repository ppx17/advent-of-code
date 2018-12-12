<?php
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");

$lines = explode("\n", $data);
$state = str_split((explode(": ", $lines[0]))[1], 1);

preg_match_all(
    "/(?<pattern>[#\.]+) => (?<replace>[#\.])/",
    $data,
    $matches,
    PREG_SET_ORDER);

$rules = array_combine(
    array_column($matches, 'pattern'),
    array_column($matches, 'replace')
);

function plantCount(array $state): int
{
    $sum = 0;
    foreach ($state as $pot => $plant) {
        if ($plant === '#') {
            $sum += $pot;
        }
    }
    return $sum;
}

function runGeneration(array $state, array $rules): array
{
    $newState = [];
    $min = array_search('#', $state) - 2;
    $max = array_search('#', array_reverse($state, true)) + 2;
    for ($pot = $min; $pot <= $max; $pot++) {
        $search = ($state[$pot - 2] ?? '.') .
            ($state[$pot - 1] ?? '.') .
            ($state[$pot] ?? '.') .
            ($state[$pot + 1] ?? '.') .
            ($state[$pot + 2] ?? '.');
        $newState[$pot] = $rules[$search];
    }
    $state = $newState;
    return $state;
}

for ($generation = 1; $generation <= 20; $generation++) {
    $state = runGeneration($state, $rules);
}

echo "Part 1: " . plantCount($state) . PHP_EOL;

$initial = 0;
$increment = 0;
// The first 100 produce a unique number, every 10 after that only produces a fixed increment.
for (null; $generation <= 110; $generation++) {
    $state = runGeneration($state, $rules);
    if ($generation === 100) {
        $initial = plantCount($state);
    } elseif ($generation === 110) {
        $increment = plantCount($state) - $initial;
    }
}

$rounds = (50000000000 / 10) - 10;
echo "Part 2: " . ($initial + ($rounds * $increment)) . PHP_EOL;
