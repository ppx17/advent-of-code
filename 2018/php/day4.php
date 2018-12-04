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

usort($guards, function($a, $b) {
    return $b['totalMinutes'] - $a['totalMinutes'];
});

$mostAsleep = $guards[0];
$max = 0; $maxMin = null;
foreach($mostAsleep['minutes'] as $min => $time) {
    if($time > $max) {
        $max = $time;
        $maxMin = $min;
    }
}

echo "Part 1: ".($mostAsleep['id'] * $maxMin).PHP_EOL;

$mostTimes = 0; $mostMinute = null; $guardId = null;
foreach($guards as $guard) {
    foreach($guard['minutes'] as $minute => $times) {
        if($times > $mostTimes) {
            $mostTimes = $times;
            $mostMinute = $minute;
            $guardId = $guard['id'];
        }
    }
}

echo "Part 2: ".($guardId * $mostMinute).PHP_EOL;
