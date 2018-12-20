<?php

use Ppx17\Aoc2018\DayRunner;
use Ppx17\Aoc2018\Result;

require_once 'vendor/autoload.php';

ini_set('memory_limit', '256M');

$runner = new DayRunner();

$runner->setDays([
    \Ppx17\Aoc2018\Days\Day1\Day1::class,   //    6 ms
    \Ppx17\Aoc2018\Days\Day2\Day2::class,   //   10 ms
    \Ppx17\Aoc2018\Days\Day3\Day3::class,   //   25 ms
    \Ppx17\Aoc2018\Days\Day4\Day4::class,   //    1 ms
    \Ppx17\Aoc2018\Days\Day5\Day5::class,   //   33 ms
    \Ppx17\Aoc2018\Days\Day6\Day6::class,   //  670 ms
    \Ppx17\Aoc2018\Days\Day7\Day7::class,   //    1 ms
    \Ppx17\Aoc2018\Days\Day8\Day8::class,   //   13 ms
    \Ppx17\Aoc2018\Days\Day9\Day9::class,   // 1557 ms
    \Ppx17\Aoc2018\Days\Day10\Day10::class, //    2 ms
    \Ppx17\Aoc2018\Days\Day11\Day11::class, // 3050 ms
    \Ppx17\Aoc2018\Days\Day12\Day12::class, //    3 ms
    \Ppx17\Aoc2018\Days\Day13\Day13::class, //   46 ms
    \Ppx17\Aoc2018\Days\Day14\Day14::class, //      ms
    \Ppx17\Aoc2018\Days\Day15\Day15::class, //      ms
    \Ppx17\Aoc2018\Days\Day16\Day16::class, //    6 ms
    \Ppx17\Aoc2018\Days\Day17\Day17::class, //   50 ms
    \Ppx17\Aoc2018\Days\Day18\Day18::class, // 1800 ms
    \Ppx17\Aoc2018\Days\Day19\Day19::class, // 4200 ms
    \Ppx17\Aoc2018\Days\Day20\Day20::class, //  128 ms
    \Ppx17\Aoc2018\Days\Day21\Day21::class, //  128 ms
    \Ppx17\Aoc2018\Days\Day22\Day22::class, //  128 ms
    \Ppx17\Aoc2018\Days\Day23\Day23::class, //  128 ms
    \Ppx17\Aoc2018\Days\Day24\Day24::class, //  128 ms
    \Ppx17\Aoc2018\Days\Day25\Day25::class, //  128 ms
]);

$options = getopt('', ['day:']);

$printResult = function (Result $result) {
    printf(" - %s %s in %s ms %.2f MiB.\n",
        str_pad($result->getName(), 5, ' '),
        $result->getStatus(),
        str_pad(round($result->getTotalRuntimeMs(), 2), 7, ' ', STR_PAD_LEFT),
        $result->getTotalMemoryUsedBytes() / (1024 * 1024));
};

if (isset($options['day'])) {
    $result = $runner->run($options['day'], $printResult);

    printf("Output part 1:\n------\n%s\n------\n\nOutput part 2:\n------\n%s\n------\n\n",
        $result->getPart1(), $result->getPart2());
} else {
    $results = $runner->runAll($printResult);
}



