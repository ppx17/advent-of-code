<?php

namespace Ppx17\Aoc2018\Days\Day8;


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