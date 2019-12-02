<?php

namespace Ppx17\Aoc2019\Aoc\Days\Day2;

class IntCode
{
    public array $memory;
    public int $pointer = 0;
    protected array $initialMemory;

    public function __construct(array $memory)
    {
        $this->memory = $this->initialMemory = $memory;
    }

    public function run(int $noun, int $verb): int
    {
        $this->memory[1] = $noun;
        $this->memory[2] = $verb;

        while (true) {
            $instruction = $this->memory[$this->pointer];

            if ($instruction === 99) {
                return $this->memory[0];
            }

            $this->runInstruction($instruction);
        }
        throw new \RuntimeException('Instruction limit reached');
    }

    public function reset(): void
    {
        $this->pointer = 0;
        $this->memory = $this->initialMemory;
    }

    protected function runInstruction($instruction): void
    {
        if ($instruction === 1) {
            $this->opAdd();
            return;
        } elseif ($instruction === 2) {
            $this->opMultiply();
            return;
        }

        throw new \RuntimeException('Invalid instruction (' . $instruction . ')');
    }

    protected function opAdd(): void
    {
        $this->memory[$this->memory[$this->pointer + 3]] = $this->a() + $this->b();
        $this->pointer += 4;
    }

    protected function opMultiply(): void
    {
        $this->memory[$this->memory[$this->pointer + 3]] = $this->a() * $this->b();
        $this->pointer += 4;
    }

    protected function a(): int
    {
        return $this->memory[$this->memory[$this->pointer + 1]];
    }

    protected function b(): int
    {
        return $this->memory[$this->memory[$this->pointer + 2]];
    }
}
