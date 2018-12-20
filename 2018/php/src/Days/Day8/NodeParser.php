<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 15:14
 */

namespace Ppx17\Aoc2018\Days\Day8;


class NodeParser
{
    private $numbers;
    private $index;

    public function __construct(array $numbers)
    {
        $this->numbers = $numbers;
    }

    public function parse(): array
    {
        $this->index = 0;
        return $this->parseNodes(1);
    }

    private function parseNodes(int $childCount)
    {
        $nodes = [];
        for ($c = 0; $c < $childCount; $c++) {
            $node = new Node(
                $this->numbers[$this->index++],
                $this->numbers[$this->index++]
            );
            $node->setChildren($this->parseNodes($node->getNumChildren()));
            $node->setMetadata(array_slice($this->numbers, $this->index, $node->getNumMeta()));
            $this->index += $node->getNumMeta();
            $nodes[] = $node;
        }

        return $nodes;
    }

}