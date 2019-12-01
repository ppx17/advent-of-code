<?php


namespace Ppx17\Aoc2019\Aoc\Runner\Validator;


class Part
{
    /** @var int */
    private $partNumber;
    /** @var string */
    private $expectation = null;
    /** @var string */
    private $result = null;

    public function __construct(int $partNumber)
    {
        $this->partNumber = $partNumber;
    }

    public function getExpectation(): ?string
    {
        return $this->expectation;
    }

    public function setExpectation(string $expectation): void
    {
        $this->expectation = $expectation;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): void
    {
        $this->result = $result;
    }

    public function isValid(): bool
    {
        return $this->hasExpectation()
            && $this->getExpectation() === $this->getResult();
    }

    public function hasExpectation()
    {
        return $this->getExpectation() !== null;
    }

    public function getPartNumber(): int
    {
        return $this->partNumber;
    }
}