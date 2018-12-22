<?php
namespace Ppx17\Aoc2018\Days\Day19;


use Ppx17\Aoc2018\Days\Common\InstructionSet;

class Processor
{
    protected $instructions;
    protected $previousIp;
    protected $ipRegister;
    protected $codeCount;
    protected $code;
    protected $registers = [0, 0, 0, 0, 0, 0];

    public function __construct(InstructionSet $instructions, string $code)
    {
        $this->instructions = $instructions;
        $this->parseCode($code);
    }

    public function execute(): int
    {
        while ($this->runInstruction()) {
            if ($this->getIp() === 34) {
                $masterNumber = $this->registers[$this->code[$this->previousIp]['input'][3]];
                return $this->sumOfFactors($masterNumber);
                break;
            }
        }

        return $this->register(0);
    }

    public function register(int $register, ?int $value = null): ?int
    {
        if ($value !== null) {
            $this->registers[$register] = $value;
        }

        return $this->registers[$register];
    }

    protected function parseCode(string $code): void
    {
        $lines = explode("\n", trim($code));
        $this->ipRegister = (explode(" ", $lines[0]))[1];
        $this->code = [];
        array_shift($lines);
        foreach ($lines as $line) {
            $parts = explode(' ', $line);
            $name = $parts[0];
            InstructionSet::arrayToNumbers($parts);
            $this->code[] = [
                'name' => $name,
                'input' => $parts
            ];
        }
        $this->codeCount = count($this->code);
    }

    protected function isValidIp(): bool
    {
        return $this->getIp() < $this->codeCount;
    }

    protected function runInstruction(): bool
    {
        if (!$this->isValidIp()) {
            return false;
        }

        $this->instructions->runInstruction(
            $this->code[$this->getIp()]['name'],
            $this->code[$this->getIp()]['input'],
            $this->registers);
        $this->previousIp = $this->getIp();

        $this->upIp();

        return true;
    }

    protected function getIp(): int {
        return $this->registers[$this->ipRegister];
    }

    protected function upIp(): void {
        $this->registers[$this->ipRegister]++;
    }

    private function sumOfFactors(int $number): int
    {
        $sum = 0;

        for ($i = 2; $i <= sqrt($number); $i++) {
            if ($number % $i == 0) {
                if ($i == ($number / $i)) {
                    $sum += $i;
                } else {
                    $sum += ($i + $number / $i);
                }
            }
        }

        return ($sum + $number + 1);
    }
}