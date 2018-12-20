<?php

namespace Ppx17\Aoc2018;

class DayRunner
{
    private $days;

    public function setDays(array $days)
    {
        $this->days = [];
        foreach ($days as $day) {
            $this->days[$this->classToIdentifier($day)] = $day;
        }
    }

    public function runAll(callable $callable): array
    {
        $results = [];
        foreach ($this->days as $day) {
            $results[] = $this->run($day, $callable);
        }

        return $results;
    }

    public function run(string $day, callable $callable): Result
    {
        if (!strstr($day, '\\')) {
            if (!isset($this->days[$day])) {
                $day = 'day'.$day;
                if (!isset($this->days[$day])) {
                    die('Day "'.$day.'" is unknown.');
                }
            }
            $day = $this->days[$day];
        }
        $result = $this->runDay($day);
        $callable($result);
        return $result;
    }

    private function runDay(string $day): Result
    {
        if (!class_exists($day)) {
            throw new \Exception('Class ' . $day . ' not found.');
        }

        $data = $this->input($day);

        $timers = new Timers();

        $result = new Result($this->classToIdentifier($day));

        /** @var Runnable $instance */
        $instance = new $day($data);
        $timers->measure('init');

        $result->setPart1($instance->part1());
        $timers->measure('part1');

        $result->setPart2($instance->part2());
        $timers->measure('part2');

        $timingResult = $timers->results();
        $result
            ->setTotalMemoryUsedBytes($timingResult['_total']['mem'])
            ->setTotalRuntimeMs($timingResult['_total']['ms']);

        $knownSolution = $this->solution($day);

        $solution = sprintf("Part 1: %s\nPart 2: %s\n", $result->getPart1(), $result->getPart2());

        if ($knownSolution === null) {
            $result->setStatusUnknown();
        } else {
            if ($this->trim($knownSolution) == $this->trim($solution)) {
                $result->setStatusCorrect();
            } else {
                printf("Expected:\n------\n%s\n------\n\nGot:\n------\n%s\n------\n\n",
                    trim($knownSolution), trim($solution));

                $result->setStatusWrong();
            }
        }

        return $result;
    }

    private function trim(string $data) {
        return trim(preg_replace('#\n|\r#', '', $data));
    }

    /**
     * @param $day
     * @return string
     * @throws \Exception
     */
    private function input($day): string
    {
        $fileName = '../input/input-' . $this->classToIdentifier($day) . '.txt';

        if (!file_exists($fileName)) {
            throw new \Exception('Input file ' . $fileName . ' not found.');
        }

        return file_get_contents($fileName);
    }

    private function solution(string $day): ?string
    {
        $fileName = '../expected/' . $this->classToIdentifier($day) . '.txt';

        if (!file_exists($fileName)) {
            return null;
        }

        return file_get_contents($fileName);
    }

    private function classToIdentifier($day): string
    {
        return strtolower(class_basename($day));
    }
}