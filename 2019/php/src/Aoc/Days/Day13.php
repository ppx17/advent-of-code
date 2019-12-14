<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Ppx17\Aoc2019\Aoc\Days\Day13\Arcade;

class Day13 extends AbstractDay
{
    private Arcade $arcade;

    public function dayNumber(): int
    {
        return 13;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->arcade = new Arcade(array_map('intval', explode(',', $this->getInput())));
    }

    public function part1(): string
    {
        $this->arcade->run();
        return (string)$this->arcade->tilesToBreak();
    }

    /**
     * We can probably take a shortcut here by not playing the whole map and simply multiply the score for 1 brick
     * with the total brick count...
     * But turn the display on and you'll see why we just simulate the whole game!
     */
    public function part2(): string
    {
        $this->arcade->reset();
        $this->arcade->enableAutoPilot();
        $this->arcade->display = false;
        $this->arcade->run(2);

        return (string)$this->arcade->score;
    }
}
