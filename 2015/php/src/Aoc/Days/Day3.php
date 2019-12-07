<?php


namespace Ppx17\Aoc2015\Aoc\Days;


use Illuminate\Support\Collection;
use Ppx17\Aoc2015\Aoc\Common\Vector;

class Day3 extends AbstractDay
{
    private Collection $instructions;

    public function dayNumber(): int
    {
        return 3;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->instructions = collect(str_split($this->getInput()));
    }

    public function part1(): string
    {
        $santa = Vector::create(0, 0);
        $visited = [
            ((string)$santa) => true
        ];
        $this->instructions
            ->each(function (string $instruction) use (&$santa, &$visited) {
                $this->processInstruction($santa, $instruction);
                $visited[(string)$santa] = true;
            });
        return count($visited);
    }

    public function part2(): string
    {
        $santa = Vector::create(0, 0);
        $roboSanta = Vector::create(0, 0);
        $visited = [
            ((string)$santa) => true
        ];
        $this->instructions
            ->each(function (string $instruction, int $index) use ($santa, $roboSanta, &$visited) {
                $active = ($index % 2) === 0 ? $santa : $roboSanta;
                $this->processInstruction($active, $instruction);
                $visited[(string)$active] = true;
            });
        return count($visited);
    }

    private function processInstruction(Vector $vector, string $instruction)
    {
        switch ($instruction) {
            case '^':
                $vector->up();
                break;
            case 'v':
                $vector->down();
                break;
            case '<':
                $vector->left();
                break;
            case '>':
                $vector->right();
                break;
        }
    }
}
