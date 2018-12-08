<?php
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");

$numbers = array_map("intval", explode(' ', $data));

class Node
{
    private $numChildren;
    private $numMeta;
    private $metadata;
    private $children;

    public function __construct(int $numChildren, int $numMeta)
    {
        $this->numChildren = $numChildren;
        $this->numMeta = $numMeta;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    public function getMetadataSum(): int
    {
        return array_sum($this->metadata);
    }

    public function getRecursiveMetadataSum(): int
    {
        return $this->getMetadataSum() + array_sum(array_map(
                function ($node) {
                    return $node->getRecursiveMetadataSum();
                },
                $this->children));
    }

    public function getNumChildren(): int
    {
        return $this->numChildren;
    }

    public function getNumMeta(): int
    {
        return $this->numMeta;
    }

    public function getValue(): int
    {
        if (count($this->children) === 0) {
            return $this->getMetadataSum();
        }
        $value = 0;
        foreach ($this->metadata as $metadataEntry) {
            $key = $metadataEntry - 1;
            if (isset($this->children[$key])) {
                $value += $this->children[$key]->getValue();
            }
        }
        return $value;
    }
}

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

$parsers = new NodeParser($numbers);
$tree = $parsers->parse();
echo "Part 1: " . $tree[0]->getRecursiveMetadataSum() . PHP_EOL;
echo "Part 2: " . $tree[0]->getValue();

