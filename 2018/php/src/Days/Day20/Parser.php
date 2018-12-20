<?php
namespace Ppx17\Aoc2018\Days\Day20;

use Ppx17\Aoc2018\Days\Common\Vector;

class Parser
{
    private $regex;
    private $nodes;
    private $initialNode;

    public function __construct(string $regex)
    {
        $this->regex = substr($regex, 1, -1);
        $this->nodes = new NodeCollection();
    }

    public function parse(): void
    {
        $this->initialNode = new Node(new Vector(0, 0));
        $this->nodes = new NodeCollection();
        $this->nodes->addNode($this->initialNode);

        $this->parseSection($this->regex, $this->initialNode);
    }

    public function getInitialNode(): Node
    {
        return $this->initialNode;
    }

    public function getNodes(): NodeCollection
    {
        return $this->nodes;
    }

    private function parseSection(string $regex, Node $location): void
    {
        if ($regex === '' || $regex[0] === ')') {
            return;
        }

        for ($i = 0; $i < strlen($regex); $i++) {
            $character = $regex[$i];
            if ($character === 'N' || $character === 'E' || $character === 'S' || $character === 'W') {
                $node = $this->nodes->findNeighbor($location, $character) ?? $location->newNode($character);
                $node->createLink($character, $location);
                $location = $node;
                $this->nodes->addNode($node);
            } elseif ($character === '(') {
                $stackHeight = 1;
                $groups = [];
                $group = '';
                while ($stackHeight > 0) {
                    $i++;
                    if ($regex[$i] === '(') {
                        $stackHeight++;
                    } elseif ($regex[$i] === ')') {
                        $stackHeight--;
                    }

                    if ($stackHeight === 1 && $regex[$i] === '|') {
                        if (strlen($group) > 0) {
                            $groups[] = $group;
                        }
                        $group = '';
                    } else {
                        $group .= $regex[$i];
                    }
                }
                if (strlen($group) > 0) {
                    $groups[] = $group;
                }

                foreach ($groups as $group) {
                    $this->parseSection($group, $location);
                }
            }
        }
    }
}