<?php

namespace Ppx17\Aoc2018\Days\Day14;


class ScoreSearcher
{
    private $pattern;
    private $scoreboard;
    private $count;
    private $patternLength;
    private const BATCH_SIZE = 10000;
    private const LOOP_LIMIT = 20000000;
    private const MIN_BATCH_CHECK_COUNT = 15000000;
    private $part1 = '';

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
        $this->patternLength = strlen($pattern);
    }

    public function search(): string
    {
        $this->scoreboard = '';
        $this->count = 0;
        $this->addScore(3);
        $this->addScore(7);

        $part1Target = intval($this->pattern) + 10;

        $first = 0;
        $second = 1;

        $loop = 0;

        while ($loop++ < self::LOOP_LIMIT) {
            $sum = $this->scoreboard[$first] + $this->scoreboard[$second];
            if ($sum < 10) {
                $this->addScore($sum);
            } else {
                $this->addScore(floor($sum / 10));
                $this->addScore($sum % 10);
            }
            $first = (($first + 1 + $this->scoreboard[$first]) % $this->count);
            $second = (($second + 1 + $this->scoreboard[$second]) % $this->count);

            if ($this->count > self::MIN_BATCH_CHECK_COUNT && $this->count % self::BATCH_SIZE === 0) {
                $offset = $this->findPattern();
                if ($offset === false) {
                    continue;
                }
                return ($this->count - self::BATCH_SIZE) + $offset;
            }

            if($this->count === $part1Target) {
                $this->part1 = substr($this->scoreboard, -10, 10);
            }
        }

        return 'uh oh, nothing found';
    }

    public function getPart1(): string
    {
        return $this->part1;
    }

    private function findPattern()
    {
        $batch = substr($this->scoreboard, -self::BATCH_SIZE, self::BATCH_SIZE);
        return strpos($batch, $this->pattern);
    }

    private function addScore(int $rating): void
    {
        $this->scoreboard .= $rating;
        $this->count++;
    }
}