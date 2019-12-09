<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day9;

use Ppx17\Aoc2019\Aoc\Days\Day7\IntCode as Day7IntCode;

class IntCode extends Day7IntCode
{
    protected int $maxTicks = 1_000_000;
    private int $relativeBase = 0;

    public function runOpCode($opCode): void
    {
        if ($opCode === 9) { // relativeBase
            $this->relativeBase += $this->a();
            $this->pointer += 2;
            return;
        }

        parent::runOpCode($opCode);
    }

    public function reset(): void
    {
        parent::reset();
        $this->relativeBase = 0;
    }

    protected function a(): int
    {
        $aMode = $this->aMode();
        if ($aMode === 2) {
            return $this->memory[$this->memory[$this->pointer + 1] + $this->relativeBase] ?? 0;
        }
        return parent::a();
    }

    protected function b(): int
    {
        $bMode = $this->bMode();
        if ($bMode === 2) {
            return $this->memory[$this->memory[$this->pointer + 2] + $this->relativeBase] ?? 0;
        }
        return parent::b();
    }

    protected function writeC(int $value): void
    {
        if ($this->cMode() === 2) {
            $this->memory[$this->memory[$this->pointer + 3] + $this->relativeBase] = $value;
            return;
        }
        parent::writeC($value);
    }

    protected function writeA(int $value): void
    {
        if ($this->aMode() === 2) {
            $this->memory[$this->memory[$this->pointer + 1] + $this->relativeBase] = $value;
            return;
        }
        parent::writeA($value);
    }

    protected function cMode(): int
    {
        $instruction = $this->memory[$this->pointer];
        return (int)(($instruction >= 10000) ? floor(($instruction % 100000) / 10000) : 0);
    }
}
