<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;

class Day8 extends AbstractDay
{
    private Collection $instructions;

    public function dayNumber(): int
    {
        return 8;
    }

    public function setUp(): void
    {
        $this->instructions = collect($this->getInputLines())
            ->map(fn($x) => explode(' ', $x));
    }

    public function part1(): string
    {
        return $this->run($this->instructions)[1];
    }

    private function run(Collection $instructions): array
    {
        $seen = [];
        $acc = $ptr = 0;
        $instructionCount = $instructions->count();
        while (!isset($seen[$ptr])) {
            $seen[$ptr] = true;
            [$op, $param] = $instructions->get($ptr);
            switch($op) {
                case 'nop': $ptr++; break;
                case 'acc': $acc += $param; $ptr++; break;
                case 'jmp': $ptr += $param; break;
            }
            if ($ptr === $instructionCount) {
                return ['exit', $acc];
            }
        }
        return ['loop', $acc];
    }

    public function part2(): string
    {
        foreach ($this->instructions as $key => $instruction) {
            [$op, $param] = $instruction;
            if ($op === 'jmp' || $op === 'nop') {
                $copy = clone $this->instructions;
                $copy->put($key, [$op === 'jmp' ? 'nop' : 'jmp', $param]);
                [$mode, $acc] = $this->run($copy);
                if ($mode === 'exit') {
                    return $acc;
                }
            }
        }
    }
}
