<?php
$data = $data ?? file_get_contents("../input/input-".basename(__FILE__, '.php').".txt");

$ids = explode("\n", trim($data));

function part1(array $ids): int
{
    $twos = $threes = 0;
    foreach ($ids as $id) {
        $counts = [];
        for ($i = 0; $i < strlen($id); $i++) {
            $counts[$id[$i]]++;
        }
        if (in_array(2, $counts)) {
            $twos++;
        }
        if (in_array(3, $counts)) {
            $threes++;
        }
    }

    return $twos * $threes;
}

function common($first, $second): string
{
    $result = '';
    for ($i = 0; $i < strlen($first); $i++) {
        $result .= ($first[$i] === $second[$i]) ? $first[$i] : '';
    }
    return $result;
}

function part2($ids): ?string
{
    for ($fi = 0; $fi < count($ids); $fi++) {
        for ($si = $fi + 1; $si < count($ids); $si++) {
            if (levenshtein($ids[$fi], $ids[$si]) === 1) {
                return common($ids[$fi], $ids[$si]);
            }
        }
    }
    return null;
}
echo "part1: " . part1($ids) . PHP_EOL;
echo "part2: " . part2($ids);