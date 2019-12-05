<?php

namespace Ppx17\Aoc2019\Aoc\Days;

use Ppx17\Aoc2019\Aoc\Days\Day2\IntCode;

class Day2 extends AbstractDay
{
    private IntCode $intCode;

    public function setUp(): void
    {
        parent::setUp();

        $this->intCode = new IntCode(array_map('intval', explode(',', $this->getInput())));
    }

    public function dayNumber(): int
    {
        return 2;
    }

    public function part1(): string
    {
        $this->intCode->memory[1] = 12;
        $this->intCode->memory[2] = 2;
        return (string)$this->intCode->run();
    }

    public function part2($target = 19690720): string
    {
        for ($noun = 99; $noun >= 0; $noun--) {
            for ($verb = 99; $verb >= 0; $verb--) {
                $this->intCode->reset();
                $this->intCode->memory[1] = $noun;
                $this->intCode->memory[2] = $verb;
                $result = $this->intCode->run();
                if ($result === $target) {
                    return (string)(100 * $noun + $verb);
                }
            }
        }
        return '';
    }
}
