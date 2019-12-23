<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day23;

class IntCode extends \Ppx17\Aoc2019\Aoc\Days\Day13\IntCode
{
    public int $result;

    public function tick()
    {
        if ($this->isHalted) {
            return;
        }

        $instruction = $this->memory[$this->pointer];

        if ($instruction === 99) {
            $this->result = $this->memory[0];
            $this->isHalted = true;
        }

        $this->processInstruction($instruction);
    }
}
