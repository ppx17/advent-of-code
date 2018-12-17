<?php
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

class Instructions
{
    private $instructions;

    public function addInstruction(Instruction $instruction): void
    {
        $this->instructions[$instruction->getName()] = $instruction;
    }

    public function testInstructions(array $inputs, array $registers, array $expected): array
    {

        $this->arrayToNumbers($inputs);
        $this->arrayToNumbers($registers);
        $this->arrayToNumbers($expected);
        $matches = [];

        foreach ($this->instructions as $instruction) {
            $result = $instruction->run($inputs, $registers);
            if ($this->resultEquals($result, $expected)) {
                $matches[] = $instruction->getName();
            }
        }
        return $matches;
    }

    public function runInstruction(string $instructionName, array $input, array $registers): array
    {
        $this->arrayToNumbers($input);
        return $this->instructions[$instructionName]->run($input, $registers);
    }

    private function arrayToNumbers(array &$input)
    {
        foreach ($input as $key => $val) {
            $input[$key] = intval($val);
        }
    }

    private function resultEquals(array $result, array $expected)
    {
        return $result === $expected;
    }
}

$instructions = new Instructions();
$instructions->addInstruction(Instruction::new('addr',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = $registers[$inputs[1]] + $registers[$inputs[2]];
        return $result;
    })
);
$instructions->addInstruction(Instruction::new('addi',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = $registers[$inputs[1]] + $inputs[2];
        return $result;
    })
);

$instructions->addInstruction(Instruction::new('mulr',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = $registers[$inputs[1]] * $registers[$inputs[2]];
        return $result;
    })
);
$instructions->addInstruction(Instruction::new('muli',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = $registers[$inputs[1]] * $inputs[2];
        return $result;
    })
);

$instructions->addInstruction(Instruction::new('banr',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = $registers[$inputs[1]] & $registers[$inputs[2]];
        return $result;
    })
);
$instructions->addInstruction(Instruction::new('bani',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = $registers[$inputs[1]] & $inputs[2];
        return $result;
    })
);

$instructions->addInstruction(Instruction::new('borr',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = $registers[$inputs[1]] | $registers[$inputs[2]];
        return $result;
    })
);
$instructions->addInstruction(Instruction::new('bori',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = $registers[$inputs[1]] | $inputs[2];
        return $result;
    })
);

$instructions->addInstruction(Instruction::new('setr',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = $registers[$inputs[1]];
        return $result;
    })
);
$instructions->addInstruction(Instruction::new('seti',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = $inputs[1];
        return $result;
    })
);

$instructions->addInstruction(Instruction::new('gtir',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = ($inputs[1] > $registers[$inputs[2]]) ? 1 : 0;
        return $result;
    })
);
$instructions->addInstruction(Instruction::new('gtri',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = ($registers[$inputs[1]] > $inputs[2]) ? 1 : 0;
        return $result;
    })
);
$instructions->addInstruction(Instruction::new('gtrr',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = ($registers[$inputs[1]] > $registers[$inputs[2]]) ? 1 : 0;
        return $result;
    })
);

$instructions->addInstruction(Instruction::new('eqir',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = ($inputs[1] == $registers[$inputs[2]]) ? 1 : 0;
        return $result;
    })
);
$instructions->addInstruction(Instruction::new('eqri',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = ($registers[$inputs[1]] == $inputs[2]) ? 1 : 0;
        return $result;
    })
);
$instructions->addInstruction(Instruction::new('eqrr',
    function (array $inputs, array $registers) {
        $result = $registers;
        $result[$inputs[3]] = ($registers[$inputs[1]] == $registers[$inputs[2]]) ? 1 : 0;
        return $result;
    })
);

preg_match_all('#Before: \[(\d+), (\d+), (\d+), (\d+)\]' .
    '\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+' .
    'After:\s+\[(\d+), (\d+), (\d+), (\d+)\]#m',
    $data, $captures, PREG_SET_ORDER
);

$part1 = 0;

$opcodeOptions = [];
$opcodes = [];

foreach ($captures as $capture) {

    $before = array_slice($capture, 1, 4);
    $input = array_slice($capture, 5, 4);
    $expected = array_slice($capture, 9, 4);

    $options = $instructions->testInstructions($input, $before, $expected);

    if (count($options) >= 3) {
        $part1++;
    }

    $currentOpcode = intval($input[0]);

    if (!isset($opcodes[$currentOpcode])) {
        if (!isset($opcodeOptions[$currentOpcode])) {
            $opcodeOptions[$currentOpcode] = $options;
        } else {
            $opcodeOptions[$currentOpcode] = array_intersect($opcodeOptions[$currentOpcode], $options);

            if (count($opcodeOptions[$currentOpcode]) == 1) {
                $opcodes[$currentOpcode] = $opcodeOptions[$currentOpcode][0];
            }
        }
    }
}

do {
    $unresolvedOptions = count($opcodeOptions, COUNT_RECURSIVE);
    foreach ($opcodeOptions as $opcode => $options) {

        $opcodeOptions[$opcode] = array_diff($options, $opcodes);

        if (count($opcodeOptions[$opcode]) == 1) {
            $opcodes[$opcode] = array_shift($opcodeOptions[$opcode]);
        }
    }
} while (count($opcodeOptions, COUNT_RECURSIVE) < $unresolvedOptions);

$dataParts = explode("\n\n\n\n", $data, 2);

$lines = explode("\n", trim($dataParts[1]));

$register = [0, 0, 0, 0];
foreach ($lines as $line) {
    $input = explode(" ", $line);
    $result = $instructions->runInstruction(
        $opcodes[intval($input[0])],
        $input,
        $register
    );
    $register = $result;
}

echo "Part 1: " . $part1 . PHP_EOL;
echo "Part 2: ". $register[0] . PHP_EOL;
