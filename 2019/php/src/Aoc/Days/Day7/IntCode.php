<?php

namespace Ppx17\Aoc2019\Aoc\Days\Day7;

use Ppx17\Aoc2019\Aoc\Days\Day5\IntCode as Day5IntCode;

class IntCode extends Day5IntCode
{
    protected int $maxTicks = 10000;
    public array $inputList;
    public int $inputIndex =  0;
    public ?\Closure $outputCallable = null;

    public function runOpCode($opCode): void
    {
        if($opCode === 3) { // input
            if(is_null($this->inputList[$this->inputIndex]))
            {
                return;
            }
            $this->input = $this->inputList[$this->inputIndex];
            $this->inputIndex++;
        }

        parent::runOpCode($opCode);

        if($opCode === 4) { // output
            if( ! is_null($this->outputCallable) && is_callable($this->outputCallable))
            {
                ($this->outputCallable)($this->output);
            }
        }
    }

    public function reset(): void
    {
        parent::reset();
        $this->inputList = [];
        $this->inputIndex = 0;
    }
}
