<?php

namespace Ppx17\Aoc2019\Aoc\Days\Day5;

use Ppx17\Aoc2019\Aoc\Days\Day2\IntCode as Day2IntCode;

class IntCode extends Day2IntCode
{
    public int $input;
    public int $output;

    public function reset(): void
    {
        parent::reset();
        $this->output = 0;
    }

    protected function processInstruction($instruction): void
    {
        parent::processInstruction($instruction % 100);
    }

    protected function runOpCode($opCode): void
    {
        switch ($opCode) {
            case 1: // add
            case 2: // multiply
                parent::runOpCode($opCode);
                return;
            case 3: // input
                $this->writeA($this->input);
                $this->pointer += 2;
                return;
            case 4: // output
                $this->output = $this->a();
                $this->pointer += 2;
                return;
            case 5: // jump-if-true
                if ($this->a() !== 0) {
                    $this->pointer = $this->b();
                } else {
                    $this->pointer += 3;
                }
                return;
            case 6: // jump-if-false
                if ($this->a() === 0) {
                    $this->pointer = $this->b();
                } else {
                    $this->pointer += 3;
                }
                return;
            case 7: // less than
                $this->writeC(($this->a() < $this->b()) ? 1 : 0);
                $this->pointer += 4;
                return;
            case 8: //equals
                $this->writeC(($this->a() === $this->b()) ? 1 : 0);
                $this->pointer += 4;
                return;
            default:
                throw new \RuntimeException('Invalid opcode (' . $opCode . ')');
        }
    }

    protected function a(): int
    {
        return ($this->aMode() === 0)
            ? $this->memory[$this->memory[$this->pointer + 1]]
            : $this->memory[$this->pointer + 1];
    }

    protected function b(): int
    {
        return (
            ($this->bMode() === 0)
                ? $this->memory[$this->memory[$this->pointer + 2]]
                : $this->memory[$this->pointer + 2]
            ) ?? 0;
    }

    protected function aMode(): int
    {
        $instruction = $this->memory[$this->pointer];
        return (int)(($instruction >= 100) ? floor(($instruction % 1000) / 100) : 0);
    }

    protected function bMode(): int
    {
        $instruction = $this->memory[$this->pointer];
        return (int)(($instruction >= 1000) ? floor(($instruction % 10000) / 1000) : 0);
    }

    protected function writeA(int $value): void {
        $this->memory[$this->memory[$this->pointer + 1]] = $value;
    }
}

