<?php

namespace Ppx17\Aoc2018\Days\Common\AStar;

abstract class BaseNode implements AStarNode
{
    private $parent;

    private $g;
    private $h;

    public function getParent(): ?AStarNode
    {
        return $this->parent;
    }

    public function setParent(AStarNode $parent): AStarNode
    {
        $this->parent = $parent;
        return $this;
    }

    public function getF(): int
    {
        return $this->getG() + $this->getH();
    }

    public function setG(int $score): AStarNode
    {
        $this->g = $score;
        return $this;
    }

    public function getG(): int
    {
        return $this->g;
    }

    public function setH(int $score): AStarNode
    {
        $this->h = $score;
        return $this;
    }

    public function getH(): int
    {
        return $this->h;
    }
}
