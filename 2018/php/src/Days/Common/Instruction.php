<?php
namespace Ppx17\Aoc2018\Days\Common;

class Instruction
{
    private $name;
    private $closure;

    public function __construct(string $name, callable $closure)
    {
        $this->name = $name;
        $this->closure = $closure;
    }

    public static function new(string $name, callable $closure): self
    {
        return new self($name, $closure);
    }

    public function run(array $inputs, array &$registers): void
    {
        $callable = $this->closure;
        $callable($inputs, $registers);
    }

    public function getName(): string
    {
        return $this->name;
    }
}