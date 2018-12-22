<?php

namespace Ppx17\Aoc2018\Days\Common\AStar;

interface AStarNode
{
    public function getID(): string;
    public function setParent(AStarNode $parent): AStarNode;
    public function getParent(): ?AStarNode;
    public function getF(): int;
    public function setG(int $score): AStarNode;
    public function getG(): int;
    public function setH(int $score): AStarNode;
    public function getH(): int;
}
