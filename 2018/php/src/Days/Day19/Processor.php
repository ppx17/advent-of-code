<?php
namespace Ppx17\Aoc2018\Days\Day19;


use Ppx17\Aoc2018\Days\Common\InstructionSet;

class Processor
{
    private $instructions;
    private $ip;
    private $previousIp;
    private $ipRegister;
    private $codeCount;
    private $code;
    private $registers = [0, 0, 0, 0, 0, 0];

    public function __construct(InstructionSet $instructions, string $code)
    {
        $this->instructions = $instructions;
        $this->parseCode($code);
    }

    public function execute(): int
    {
        while ($this->runInstruction()) {
            if ($this->ip === 34) {
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

    private function parseCode(string $code): void
    {
        $lines = explode("\n", trim($code));
        $this->ipRegister = (explode(" ", $lines[0]))[1];
        $this->code = [];
        array_shift($lines);
        foreach ($lines as $line) {
            $parts = explode(' ', $line);
            $this->code[] = [
                'name' => $parts[0],
                'input' => $parts
            ];
        }
        $this->codeCount = count($this->code);
    }

    private function isValidIp(): bool
    {
        return $this->ip < $this->codeCount;
    }

    private function runInstruction(): bool
    {
        $this->ip = $this->registers[$this->ipRegister];

        if (!$this->isValidIp()) {
            return false;
        }

        $this->registers = $this->instructions->runInstruction(
            $this->code[$this->ip]['name'],
            $this->code[$this->ip]['input'],
            $this->registers);
        $this->previousIp = $this->ip;

        $this->ip = $this->registers[$this->ipRegister];
        $this->ip++;
        $this->registers[$this->ipRegister] = $this->ip;

        return true;
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