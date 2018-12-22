<?php

namespace Ppx17\Aoc2018\Days\Common;


class InstructionSet
{
    private $instructions;

    public function __construct()
    {
        $this->loadInstructions();
    }

    public static function arrayToNumbers(array &$input): void
    {
        foreach ($input as $key => $val) {
            $input[$key] = intval($val);
        }
    }

    public function addInstruction(Instruction $instruction): void
    {
        $this->instructions[$instruction->getName()] = $instruction;
    }

    public function runInstruction(string $instructionName, array $input, array &$registers): void
    {
        $this->instructions[$instructionName]->run($input, $registers);
    }

    public function testInstructions(array $inputs, array $registers, array $expected): array
    {

        self::arrayToNumbers($inputs);
        self::arrayToNumbers($registers);
        self::arrayToNumbers($expected);
        $matches = [];

        foreach ($this->instructions as $instruction) {
            $result = $registers;
            $instruction->run($inputs, $result);
            if ($result === $expected) {
                $matches[] = $instruction->getName();
            }
        }
        return $matches;
    }

    private function loadInstructions(): void
    {
        $this->addInstruction(Instruction::new('addr',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = $registers[$inputs[1]] + $registers[$inputs[2]];
            })
        );
        $this->addInstruction(Instruction::new('addi',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = $registers[$inputs[1]] + $inputs[2];
            })
        );

        $this->addInstruction(Instruction::new('mulr',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = $registers[$inputs[1]] * $registers[$inputs[2]];
            })
        );
        $this->addInstruction(Instruction::new('muli',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = $registers[$inputs[1]] * $inputs[2];
            })
        );

        $this->addInstruction(Instruction::new('banr',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = $registers[$inputs[1]] & $registers[$inputs[2]];
            })
        );
        $this->addInstruction(Instruction::new('bani',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = $registers[$inputs[1]] & $inputs[2];
            })
        );

        $this->addInstruction(Instruction::new('borr',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = $registers[$inputs[1]] | $registers[$inputs[2]];
            })
        );
        $this->addInstruction(Instruction::new('bori',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = $registers[$inputs[1]] | $inputs[2];
            })
        );

        $this->addInstruction(Instruction::new('setr',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = $registers[$inputs[1]];
            })
        );
        $this->addInstruction(Instruction::new('seti',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = $inputs[1];
            })
        );

        $this->addInstruction(Instruction::new('gtir',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = ($inputs[1] > $registers[$inputs[2]]) ? 1 : 0;
            })
        );
        $this->addInstruction(Instruction::new('gtri',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = ($registers[$inputs[1]] > $inputs[2]) ? 1 : 0;
            })
        );
        $this->addInstruction(Instruction::new('gtrr',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = ($registers[$inputs[1]] > $registers[$inputs[2]]) ? 1 : 0;
            })
        );

        $this->addInstruction(Instruction::new('eqir',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = ($inputs[1] == $registers[$inputs[2]]) ? 1 : 0;
            })
        );
        $this->addInstruction(Instruction::new('eqri',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = ($registers[$inputs[1]] == $inputs[2]) ? 1 : 0;
            })
        );
        $this->addInstruction(Instruction::new('eqrr',
            function (array $inputs, array &$registers) {
                $registers[$inputs[3]] = ($registers[$inputs[1]] == $registers[$inputs[2]]) ? 1 : 0;
            })
        );
    }
}