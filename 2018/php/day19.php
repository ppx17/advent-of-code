<?php

namespace aoc2018\day19;

use Closure;

$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");

class Instruction
{
    private $name;
    private $closure;

    public function __construct(string $name, Closure $closure)
    {
        $this->name = $name;
        $this->closure = $closure;
    }

    public static function new(string $name, Closure $closure): self
    {
        return new self($name, $closure);
    }

    public function run(array $inputs, array $registers): array
    {
        return $this->closure->call($this, $inputs, $registers);
    }

    public function getName(): string
    {
        return $this->name;
    }
}

class InstructionSet
{
    private $instructions;

    public function __construct()
    {
        $this->loadInstructions();
    }

    public function addInstruction(Instruction $instruction): void
    {
        $this->instructions[$instruction->getName()] = $instruction;
    }

    public function runInstruction(string $instructionName, array $input, array $registers): array
    {
        $this->arrayToNumbers($input);
        return $this->instructions[$instructionName]->run($input, $registers);
    }

    private function arrayToNumbers(array &$input): void
    {
        foreach ($input as $key => $val) {
            $input[$key] = intval($val);
        }
    }

    private function loadInstructions(): void
    {
        $this->addInstruction(Instruction::new('addr',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = $registers[$inputs[1]] + $registers[$inputs[2]];
                return $result;
            })
        );
        $this->addInstruction(Instruction::new('addi',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = $registers[$inputs[1]] + $inputs[2];
                return $result;
            })
        );

        $this->addInstruction(Instruction::new('mulr',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = $registers[$inputs[1]] * $registers[$inputs[2]];
                return $result;
            })
        );
        $this->addInstruction(Instruction::new('muli',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = $registers[$inputs[1]] * $inputs[2];
                return $result;
            })
        );

        $this->addInstruction(Instruction::new('banr',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = $registers[$inputs[1]] & $registers[$inputs[2]];
                return $result;
            })
        );
        $this->addInstruction(Instruction::new('bani',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = $registers[$inputs[1]] & $inputs[2];
                return $result;
            })
        );

        $this->addInstruction(Instruction::new('borr',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = $registers[$inputs[1]] | $registers[$inputs[2]];
                return $result;
            })
        );
        $this->addInstruction(Instruction::new('bori',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = $registers[$inputs[1]] | $inputs[2];
                return $result;
            })
        );

        $this->addInstruction(Instruction::new('setr',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = $registers[$inputs[1]];
                return $result;
            })
        );
        $this->addInstruction(Instruction::new('seti',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = $inputs[1];
                return $result;
            })
        );

        $this->addInstruction(Instruction::new('gtir',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = ($inputs[1] > $registers[$inputs[2]]) ? 1 : 0;
                return $result;
            })
        );
        $this->addInstruction(Instruction::new('gtri',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = ($registers[$inputs[1]] > $inputs[2]) ? 1 : 0;
                return $result;
            })
        );
        $this->addInstruction(Instruction::new('gtrr',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = ($registers[$inputs[1]] > $registers[$inputs[2]]) ? 1 : 0;
                return $result;
            })
        );

        $this->addInstruction(Instruction::new('eqir',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = ($inputs[1] == $registers[$inputs[2]]) ? 1 : 0;
                return $result;
            })
        );
        $this->addInstruction(Instruction::new('eqri',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = ($registers[$inputs[1]] == $inputs[2]) ? 1 : 0;
                return $result;
            })
        );
        $this->addInstruction(Instruction::new('eqrr',
            function (array $inputs, array $registers) {
                $result = $registers;
                $result[$inputs[3]] = ($registers[$inputs[1]] == $registers[$inputs[2]]) ? 1 : 0;
                return $result;
            })
        );
    }
}

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


$cpu1 = new Processor(new InstructionSet(), $data);
$cpu2 = clone $cpu1;
$cpu2->register(0, 1);

echo "Part 1: " . $cpu1->execute() . PHP_EOL;
echo "Part 2: " . $cpu2->execute() . PHP_EOL;
