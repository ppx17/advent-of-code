<?php
$InputFile = "../input/input-day4.txt";

$events = explode("\n", trim(file_get_contents($InputFile)));
sort($events);

$guards = [];
$currentGuard = null;
$startMinute = null;

foreach ($events as $event) {
    if (preg_match('/Guard #(?<id>[0-9]+) begins shift$/', $event, $matches) !== 0) {
        $currentGuard = $matches['id'];
        if (!isset($guards[$currentGuard])) {
            $guards[$currentGuard] = [
                'id' => $currentGuard,
                'totalMinutes' => 0,
                'minutes' => [],
            ];
        }
    } elseif (preg_match('/:(?<min>[0-9]+)\] falls asleep/', $event, $matches) !== 0) {
        $startMinute = $matches['min'];
    } elseif (preg_match('/:(?<min>[0-9]+)\] wakes up/', $event, $matches) !== 0) {
        $stopMinute = $matches['min'];
        $guards[$currentGuard]['totalMinutes'] += $stopMinute - $startMinute;

        for ($i = $startMinute; $i < $stopMinute; $i++) {
            $guards[$currentGuard]['minutes'][$i]++ ?? $guards[$currentGuard]['minutes'][$i] = 1;
        }
    }
}

usort($guards, function ($a, $b) {
    return $b['totalMinutes'] - $a['totalMinutes'];
});

$mostAsleepOnMinute = array_search(max($guards[0]['minutes']), $guards[0]['minutes']);
echo "Part 1: " . ($guards[0]['id'] * $mostAsleepOnMinute) . PHP_EOL;

$mostSleptOnMinute = 0;
$globalSleepingRecord = null;
$guardWithRecord = null;
foreach ($guards as $guard) {
    if(count($guard['minutes']) === 0) continue;
    $personalSleepingRecord = max($guard['minutes']);
    if ($personalSleepingRecord > $mostSleptOnMinute) {
        $mostSleptOnMinute = $personalSleepingRecord;
        $globalSleepingRecord = array_search($personalSleepingRecord, $guard['minutes']);
        $guardWithRecord = $guard['id'];
    }
}

echo "Part 2: " . ($guardWithRecord * $globalSleepingRecord) . PHP_EOL;
