<?php

namespace Ppx17\Aoc2018\Days\Day9;


use Ppx17\Aoc2018\Days\Day;

class Day9 extends Day
{
    private $players;
    private $marbles;

    public function __construct(string $data)
    {
        parent::__construct($data);
        preg_match('/(?<players>\d+) players; last marble is worth (?<marbles>\d+) points/', $data, $matches);
        $this->players = $matches['players'];
        $this->marbles = $matches['marbles'];
    }

    public function part1(): string
    {
        return $this->getHighScore($this->players, $this->marbles);
    }

    public function part2(): string
    {
        return $this->getHighScore($this->players, $this->marbles * 100);
    }

    private function getHighScore(int $numPlayers, int $numMarbles): int
    {
        $playerScores = [];
        $board = new RotatableList();
        $board->push(0);
        for ($marble = 1; $marble <= $numMarbles; $marble++) {
            if ($marble % 23 === 0) {
                $board->rotate(-7);
                $playerScores[$marble % $numPlayers] += $board->pop() + $marble;
            } else {
                $board->rotate(2);
                $board->push($marble);
            }
        }
        return max($playerScores);
    }
}