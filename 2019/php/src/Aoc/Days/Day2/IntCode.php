<?php

namespace Ppx17\Aoc2019\Aoc\Days\Day2;

class IntCode
{
    protected int $maxTicks = 1000;
    public array $memory;
    public int $pointer = 0;
    protected array $initialMemory;

    public function __construct(array $memory)
    {
        $this->memory = $this->initialMemory = $memory;
    }

    public function run(): int
    {
        for ($tick = 0; $tick < $this->maxTicks; $tick++) {
            $instruction = $this->memory[$this->pointer];

            if ($instruction === 99) {
                return $this->memory[0];
            }

            $this->processInstruction($instruction);
        }
        throw new \RuntimeException('Instruction limit reached');
    }

    public function reset(): void
    {
        $this->pointer = 0;
        $this->memory = $this->initialMemory;
    }

    protected function runOpCode($opCode): void
    {
        switch ($opCode) {
            case 1:
                $this->opAdd();
                return;
            case 2:
                $this->opMultiply();
                return;
        }
        throw new \RuntimeException('Invalid instruction (' . $opCode . ')');
    }

    protected function opAdd(): void
    {
        $this->writeC($this->a() + $this->b());
        $this->pointer += 4;
    }

    protected function opMultiply(): void
    {
        $this->writeC($this->a() * $this->b());
        $this->pointer += 4;
    }

    protected function writeC(int $value): void
    {
        $this->memory[$this->memory[$this->pointer + 3]] = $value;
    }

    protected function a(): int
    {
        return $this->memory[$this->memory[$this->pointer + 1]];
    }

    protected function b(): int
    {
        return $this->memory[$this->memory[$this->pointer + 2]];
    }

    protected function processInstruction($instruction): void
    {
        $this->runOpCode($instruction);
    }
}
