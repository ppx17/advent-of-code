<?php
namespace Ppx17\Aoc2018\Days\Day20;


use Ppx17\Aoc2018\Days\Common\Vector;

class Node
{
    public $location;
    private $links;

    public function __construct(Vector $location)
    {
        $this->location = $location;
        $this->links = [];
    }

    public function id(): string
    {
        return $this->location->x . ':' . $this->location->y;
    }

    public function newNode(string $dir): Node
    {
        switch ($dir) {
            case 'N':
                return new Node($this->location->up());
            case 'S':
                return new Node($this->location->down());
            case 'E':
                return new Node($this->location->right());
            case 'W':
                return new Node($this->location->left());
            default:
                throw new InvalidArgumentException('Invalid direction ' . $dir);
        }
    }

    public function createLink(string $dir, Node $other): void
    {
        $this->link($dir, $other);
        $other->link($this->opposite($dir), $this);
    }

    public function link(string $direction, Node $other)
    {
        $this->links[$direction] = $other;
    }

    public function links(): array
    {
        return $this->links;
    }

    private function opposite(string $dir): string
    {
        return ([
                'N' => 'S',
                'S' => 'N',
                'E' => 'W',
                'W' => 'E'
            ])[$dir] ?? 'Unknown';
    }
}