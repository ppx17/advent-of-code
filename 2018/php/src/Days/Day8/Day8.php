<?php

namespace Ppx17\Aoc2018\Days\Day8;


use Ppx17\Aoc2018\Days\Day;

class Day8 extends Day
{
    private $nodeTree;

    public function __construct(string $data)
    {
        parent::__construct($data);
        $parser = new NodeParser(explode(' ', $data));
        $this->nodeTree = $parser->parse();
    }

    public function part1(): string
    {
        return $this->nodeTree[0]->getRecursiveMetadataSum();
    }

    public function part2(): string
    {
        return $this->nodeTree[0]->getValue();
    }
}