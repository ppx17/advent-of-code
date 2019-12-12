<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day12;

class Moon
{
    public Vector $location;
    public Vector $velocity;

    public function __construct(Vector $location)
    {
        $this->location = $location;
        $this->velocity = new Vector(0, 0, 0);
    }

    public function move()
    {
        $this->location = $this->location->add($this->velocity);
    }

    public function getDeltaV(Moon $other): Vector
    {
        return new Vector(
            $this->getSpeed($this->location->x, $other->location->x),
            $this->getSpeed($this->location->y, $other->location->y),
            $this->getSpeed($this->location->z, $other->location->z),
        );
    }

    private function getSpeed(int $myPos, int $otherPos): int
    {
        if ($myPos === $otherPos) {
            return 0;
        }
        return ($myPos > $otherPos) ? -1 : 1;
    }

    public function energy(): int {
        return $this->location->absoluteSum() * $this->velocity->absoluteSum();
    }
}
