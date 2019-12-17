<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day17;

use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Day10\Vector;
use Ppx17\Aoc2019\Aoc\Days\Day13\IntCode;
use Ppx17\Aoc2019\Aoc\Days\Day15\Direction;

class PathFinder
{
    public IntCode $computer;
    public Map $map;
    public Vector $position;
    public Direction $direction;

    private Collection $path;
    private int $steps;

    public function path(): Collection
    {
        $this->path = collect();
        $this->steps = 0;
        $tick = 0;

        while ($this->findStep() && $tick < 10_000) {
            $tick++;
        }

        return $this->path;
    }

    private function findStep(): bool
    {
        $next = $this->map->color($this->position->add($this->direction));
        if ($next === '#') {
            $this->steps++;
            $this->position = $this->position->add($this->direction);
            return true;
        }
        if ($this->steps > 0) {
            $this->path->push($this->steps);
            $this->steps = 0;
        }


        $next = $this->map->color($this->position->add($this->direction->left()));
        if ($next === '#') {
            $this->direction = $this->direction->left();
            $this->position = $this->position->add($this->direction);
            $this->path->push('L');
            $this->steps = 1;
            return true;
        }

        $next = $this->map->color($this->position->add($this->direction->right()));
        if ($next === '#') {
            $this->direction = $this->direction->right();
            $this->position = $this->position->add($this->direction);
            $this->path->push('R');
            $this->steps = 1;
            return true;
        }

        return false;
    }
}
