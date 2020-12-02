<?php


namespace Ppx17\Aoc2020\Aoc\Runner\Validator;


class Part
{
    private int $partNumber;
    private ?string $expectation = null;
    private ?string $result = null;

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
            && $this->trimLines($this->getExpectation()) === $this->trimLines($this->getResult());
    }

    public function hasExpectation()
    {
        return $this->getExpectation() !== null;
    }

    public function getPartNumber(): int
    {
        return $this->partNumber;
    }

    private function trimLines(string $input): string
    {
        return collect(explode("\n", $input))
            ->map(fn($l) => trim($l))
            ->reject(fn($l) => empty($l))
            ->join("\n");
    }
}
