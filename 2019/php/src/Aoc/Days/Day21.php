<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Ppx17\Aoc2019\Aoc\Days\Day13\IntCode;

class Day21 extends AbstractDay
{
    private IntCode $computer;
    private int $damage = 0;
    private bool $enableDisplay = false;

    public function dayNumber(): int
    {
        return 21;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->computer = new IntCode($this->getInputIntCode());
        $this->computer->outputCallable = fn($int) => $this->output($int);
    }

    public function part1(): string
    {
        $script = <<<SCRIPT
NOT B T
NOT C J
AND T J
AND D J
NOT C T
AND D T
OR T J
NOT A T
OR T J
WALK

SCRIPT;

        $this->computer->inputList = $this->compileScript($script);
        $this->computer->run();

        return (string)$this->damage;
    }

    public function part2(): string
    {
        $script = <<<SCRIPT
NOT A T
NOT B J
OR T J
NOT C T
OR T J
AND D J
AND E T
OR H T
AND T J
RUN

SCRIPT;

        $this->computer->reset();
        $this->computer->inputList = $this->compileScript($script);
        $this->computer->run();

        return (string)$this->damage;
    }

    private function output($int)
    {
        if ($int <= 255) {
            if ($this->enableDisplay) echo chr($int);
        } else {
            $this->damage = $int;
        }
    }

    /**
     * @param string $script
     * @return array
     */
    private function compileScript(string $script): array
    {
        return array_map('ord', str_split($script));
    }
}
