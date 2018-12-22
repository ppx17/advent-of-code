<?php

namespace Ppx17\Aoc2018\Days\Day21;


class Processor extends \Ppx17\Aoc2018\Days\Day19\Processor
{
    public function executePart1(): int {
        while ($this->runInstruction()) {
            if($this->code[$this->getIp()]['name'] === 'eqrr') {
                return ($this->registers[$this->code[$this->getIp()]['input'][1]]);
            }
        }
    }

    public function executePart2(): int
    {
        $last = null;
        while ($this->runInstruction()) {
            if($this->code[$this->getIp()]['name'] === 'eqrr') {
                $value = $this->registers[$this->code[$this->getIp()]['input'][1]];
                if(isset($seen[$value])) {
                    return $last;
                }else{
                    $seen[$value] = true;
                    $last = $value;
                }
            }
        }
        return 0;
    }
}