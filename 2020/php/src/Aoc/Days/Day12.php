<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;
use Ppx17\Aoc2020\Aoc\Days\Common\Vector2;

class Day12 extends AbstractDay
{
    private Collection $instructions;
    private array $windDirections;

    public function dayNumber(): int
    {
        return 12;
    }

    public function setUp(): void
    {
        $this->instructions = collect($this->getInputLines())
            ->map(fn($x) => [$x[0], (int)substr($x, 1)]);

        $this->windDirections = [
            'E' => Vector2::east(),
            'S' => Vector2::south(),
            'W' => Vector2::west(),
            'N' => Vector2::north()
        ];
    }

    public function part1(): string
    {
        return $this->sail(new Vector2(1, 0), false);
    }

    public function part2(): string
    {
        return $this->sail(new Vector2(10, -1), true);
    }

    private function sail(Vector2 $direction, bool $moveDirection): int
    {
        $position = new Vector2();
        foreach ($this->instructions as $instruction) {
            switch ($instruction[0]) {
                case 'L':
                    $direction = $direction->rotateLeftTimes($instruction[1] / 90);
                    break;
                case 'R':
                    $direction = $direction->rotateRightTimes($instruction[1] / 90);
                    break;
                case 'N':
                case 'E':
                case 'W':
                case 'S':
                    if ($moveDirection) {
                        $direction = $direction->move($this->windDirections[$instruction[0]], $instruction[1]);
                    } else {
                        $position = $position->move($this->windDirections[$instruction[0]], $instruction[1]);
                    }
                    break;
                case 'F':
                    $position = $position->move($direction, $instruction[1]);
                    break;
            }
        }

        return $position->manhattan();
    }
}
