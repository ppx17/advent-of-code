<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use SplDoublyLinkedList;

class Day22 extends AbstractDay
{
    private SplDoublyLinkedList $p1;
    private SplDoublyLinkedList $p2;

    public function dayNumber(): int
    {
        return 22;
    }

    public function setUp(): void
    {
        $this->p1 = new SplDoublyLinkedList();
        $this->p2 = new SplDoublyLinkedList();

        $parts = explode("\n\nPlayer 2:\n", $this->getInputTrimmed());
        array_map(fn($n) => $this->p1->push((int)$n), array_slice(explode("\n", $parts[0]), 1));
        array_map(fn($n) => $this->p2->push((int)$n), explode("\n", $parts[1]));
    }

    public function part1(): string
    {
        $stack1 = clone $this->p1;
        $stack2 = clone $this->p2;

        while (!$stack1->isEmpty() && !$stack2->isEmpty()) {

            $p1 = $stack1->shift();
            $p2 = $stack2->shift();

            if ($p1 > $p2) {
                $stack1->push($p1);
                $stack1->push($p2);
            } else {
                $stack2->push($p2);
                $stack2->push($p1);
            }
        }

        return $this->score($stack1->isEmpty() ? $stack2 : $stack1);
    }

    public function part2(): string
    {
        return $this->recursiveCombat(clone $this->p1, clone $this->p2);
    }

    private function recursiveCombat(SplDoublyLinkedList $stack1, SplDoublyLinkedList $stack2, bool $isSub = false): string
    {
        $seenStacks = [];

        while (!$stack1->isEmpty() && !$stack2->isEmpty()) {

            $seenStack = $this->serializeStacks($stack1, $stack2);
            if (isset($seenStacks[$seenStack])) {
                return 'p1';
            }
            $seenStacks[$seenStack] = true;

            $p1 = $stack1->shift();
            $p2 = $stack2->shift();

            if ($stack1->count() >= $p1 && $stack2->count() >= $p2) {

                $sub1 = $this->takeSubStack($stack1, $p1);
                $sub2 = $this->takeSubStack($stack2, $p2);

                $winner = $this->recursiveCombat($sub1, $sub2, true);
                if ($winner === 'p1') {
                    $stack1->push($p1);
                    $stack1->push($p2);
                } else {
                    $stack2->push($p2);
                    $stack2->push($p1);
                }
                continue;
            }
            if ($p1 > $p2) {
                $stack1->push($p1);
                $stack1->push($p2);
            } else {
                $stack2->push($p2);
                $stack2->push($p1);
            }
        }

        if ($isSub) {
            return $stack1->isEmpty() ? 'p2' : 'p1';
        }

        return $this->score($stack1->isEmpty() ? $stack2 : $stack1);
    }

    private function serializeStacks(SplDoublyLinkedList $stack1, SplDoublyLinkedList $stack2): string
    {
        return $stack1->serialize() . '+' . $stack2->serialize();
    }

    private function score(SplDoublyLinkedList $winner): int
    {
        $numbers = $winner->count();
        $score = 0;
        foreach ($winner as $number) {
            $score += $number * $numbers--;
        }

        return $score;
    }

    private function takeSubStack(SplDoublyLinkedList $original, int $cardsToTake): SplDoublyLinkedList
    {
        $new = new SplDoublyLinkedList();
        $original->rewind();
        for ($i = 0; $i < $cardsToTake; $i++) {
            $new->push($original->current());
            $original->next();
        }

        return $new;
    }
}
