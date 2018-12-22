<?php

namespace Ppx17\Aoc2018\Days\Day15;


class Unit
{
    public $hitPoints;
    public $location;
    public $type;
    public $attackPower;

    public function __construct(Vector $location, string $type)
    {
        $this->type = $type;
        $this->hitPoints = 200;
        $this->attackPower = 3;
        $this->location = $location;
    }

    public function isElf()
    {
        return $this->type === 'E';
    }

    public function isGoblin()
    {
        return $this->type === 'G';
    }
}