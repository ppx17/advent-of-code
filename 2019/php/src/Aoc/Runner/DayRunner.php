<?php


namespace Ppx17\Aoc2019\Aoc\Runner;


use Symfony\Component\Console\Output\OutputInterface;

class DayRunner
{

    public function run(DayInterface $day, OutputInterface $output = null): Result
    {
        $result = new Result($day);

        optional($output)->writeln('Setup..');
        $result->setTimeSetup(
            $this->measure(function () use ($day) {
                $day->setUp();
            })
        );

        optional($output)->writeln('Part 1..');
        $result->setTimePart1(
            $this->measure(function () use ($day, $result) {
                $result->setPart1($day->part1());
            })
        );

        optional($output)->writeln('Part 2..');
        $result->setTimePart2(
            $this->measure(function () use ($day, $result) {
                $result->setPart2($day->part2());
            })
        );


        optional($output)->writeln('Finished!..');

        return $result;
    }

    private function measure(callable $callable)
    {
        $start = microtime(true);

        $callable();

        return (microtime(true) - $start) * 1000;
    }
}