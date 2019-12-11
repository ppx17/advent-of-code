<?php

namespace Ppx17\Aoc2019\Aoc\Days;

use Ppx17\Aoc2019\Aoc\Days\Day11\Map;
use Ppx17\Aoc2019\Aoc\Days\Day11\Robot;

class Day11 extends AbstractDay
{
    private array $code;
    private Map $map;

    public function dayNumber(): int
    {
        return 11;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->code = array_map('intval', explode(',', $this->getInput()));
    }

    public function part1(): string
    {
        $robot = new Robot($this->code);
        $robot->run(0);
        return $robot->map->countTiles();
    }

    public function part2(): string
    {
        $robot = new Robot($this->code);
        $robot->run(1);
        return str_replace('.', ' ', (string)$robot->map);
    }
}
