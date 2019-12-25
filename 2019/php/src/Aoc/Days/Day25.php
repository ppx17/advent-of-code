<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Ppx17\Aoc2019\Aoc\Days\Day23\IntCode;

class Day25 extends AbstractDay
{
    private IntCode $computer;
    private string $response = '';

    public function dayNumber(): int
    {
        return 25;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->computer = new IntCode($this->getInputIntCode());
        $this->computer->outputCallable = function(int $out) {
            $this->response .= chr($out);
        };

        $script = <<<SCRIPT
north
take dark matter
east
south
take dehydrated water
north
east
take bowl of rice
west
west
north
north
take manifold
west
take jam
east
east
take candy cane
west
south
east
south
take antenna
west
take hypercube
east
north
west
south
south
west
south
west
drop manifold
drop bowl of rice
drop dark matter
drop jam
west

SCRIPT;
        /**
         * Take:
         * candy cane
         * antenna
         * dehydrated water
         * hypercube
         *
         * Leave:
         * manifold
         * dark matter
         * bowl of rice
         * jam
         */
        $this->computer->inputList = array_map("ord", str_split($script));
    }

    public function part1(): string
    {
        $this->computer->run();

        preg_match("#typing (?<code>\d+) on the keypad #", $this->response, $matches);

        return $matches['code'];
    }

    public function part2(): string
    {
        return 'Finished!';
    }
}
