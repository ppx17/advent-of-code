<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day13;

use Ppx17\Aoc2019\Aoc\Days\Day9\IntCode as Day9IntCode;

class IntCode extends Day9IntCode
{
    protected int $maxTicks = 100_000_000;
    public ?\Closure $inputCallable;
    public bool $isHalted = false;

    public function halt()
    {
        $this->isHalted = true;
        $this->maxTicks = 0; // Force stopping of run loop
    }

    public function run(): int
    {
        try {
            return parent::run();
        }catch(\RuntimeException $ex) {
            if( ! $this->isHalted) {
                throw $ex;
            }
        }
        return $this->memory[0];
    }

    public function runOpCode($opCode): void
    {
        if($opCode === 3) { // input
            if(isset($this->inputCallable) && is_callable($this->inputCallable))
            {
                $this->inputList[] = ($this->inputCallable)();
            }
        }

        parent::runOpCode($opCode);
    }
}
