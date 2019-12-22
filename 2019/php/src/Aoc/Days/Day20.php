<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Ppx17\Aoc2019\Aoc\Days\Common\AStar\AStar;
use Ppx17\Aoc2019\Aoc\Days\Day20\BaseNodeGenerator;
use Ppx17\Aoc2019\Aoc\Days\Day20\MapNodeGenerator;
use Ppx17\Aoc2019\Aoc\Days\Day20\MapNodeGeneratorRecursive;

class Day20 extends AbstractDay
{
    private array $grid;

    public function dayNumber(): int
    {
        return 20;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->grid = collect($this->getInputLines(false))
            ->map(fn($l) => str_split($l))
            ->toArray();
    }

    public function part1(): string
    {
        $generator = new MapNodeGenerator($this->grid);
        return $this->runAStar($generator);
    }

    public function part2(): string
    {
        $generator = new MapNodeGeneratorRecursive($this->grid);
        return $this->runAStar($generator);
    }

    private function runAStar(BaseNodeGenerator $generator): string
    {
        $aStar = new AStar($generator);

        $start = $generator->getPortal('AA');
        $dest = $generator->getPortal('ZZ');

        $path = $aStar->run($start, $dest);

        $lastNode = end($path);

        return (string)$lastNode->getG();
    }
}
