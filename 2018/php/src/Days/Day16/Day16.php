<?php

namespace Ppx17\Aoc2018\Days\Day16;


use Ppx17\Aoc2018\Days\Common\InstructionSet;
use Ppx17\Aoc2018\Days\Day;

class Day16 extends Day
{
    private $part1 = 0;
    private $instructions;
    private $opCodes;

    public function __construct(string $data)
    {
        parent::__construct($data);

        $this->instructions = new InstructionSet();
    }

    public function part1(): string
    {
        $this->opCodes = $this->mapInstructionsToOpCodes();
        return (string)$this->part1;
    }

    public function part2(): string
    {
        $finalRegister = $this->runProgram();
        return (string)$finalRegister[0];
    }

    private function mapInstructionsToOpCodes(): array
    {
        preg_match_all('#Before: \[(\d+), (\d+), (\d+), (\d+)\]' .
            '\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+' .
            'After:\s+\[(\d+), (\d+), (\d+), (\d+)\]#m',
            $this->data, $captures, PREG_SET_ORDER
        );

        $this->part1 = 0;

        $opCodeOptions = [];
        $opCodes = [];

        foreach ($captures as $capture) {

            $before = array_slice($capture, 1, 4);
            $input = array_slice($capture, 5, 4);
            $expected = array_slice($capture, 9, 4);

            $options = $this->instructions->testInstructions($input, $before, $expected);

            if (count($options) >= 3) {
                $this->part1++;
            }

            $currentOpCode = intval($input[0]);

            if (!isset($opCodes[$currentOpCode])) {
                if (!isset($opCodeOptions[$currentOpCode])) {
                    $opCodeOptions[$currentOpCode] = $options;
                } else {
                    $opCodeOptions[$currentOpCode] = array_intersect($opCodeOptions[$currentOpCode], $options);

                    if (count($opCodeOptions[$currentOpCode]) == 1) {
                        $opCodes[$currentOpCode] = $opCodeOptions[$currentOpCode][0];
                    }
                }
            }
        }

        do {
            $unresolvedOptions = count($opCodeOptions, COUNT_RECURSIVE);
            foreach ($opCodeOptions as $opcode => $options) {

                $opCodeOptions[$opcode] = array_diff($options, $opCodes);

                if (count($opCodeOptions[$opcode]) == 1) {
                    $opCodes[$opcode] = array_shift($opCodeOptions[$opcode]);
                }
            }
        } while (count($opCodeOptions, COUNT_RECURSIVE) < $unresolvedOptions);

        return $opCodes;
    }

    private function runProgram(): array {
        $dataParts = explode("\n\n\n\n", $this->data, 2);

        $lines = explode("\n", trim($dataParts[1]));

        $register = [0, 0, 0, 0];
        foreach ($lines as $line) {
            $input = explode(" ", $line);
            $this->instructions->runInstruction(
                $this->opCodes[intval($input[0])],
                $input,
                $register
            );
        }

        return $register;
    }
}