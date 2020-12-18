<?php


namespace Ppx17\Aoc2020\Aoc\Days;


class Day18 extends AbstractDay
{
    public function dayNumber(): int
    {
        return 18;
    }

    public function part1(): string
    {
        return array_sum(array_map(fn($x) => $this->solveExpressionPart1($x), $this->getInputLines()));
    }

    public function part2(): string
    {
        return array_sum(array_map(fn($x) => $this->solveExpressionPart2($x), $this->getInputLines()));
    }

    private function solveExpressionPart1(string $expression): int
    {
        $expression = $this->solveParentheses($expression, [$this, 'solveExpressionPart1']);
        return $this->solveAddAndMulti($expression);
    }

    private function solveExpressionPart2(string $expression): int
    {
        $expression = $this->solveParentheses($expression, [$this, 'solveExpressionPart2']);
        $expression = $this->solveAddition($expression);
        return $this->solveMultiplication($expression);
    }

    private function solveParentheses(string $expression, callable $expressionResolver): string
    {
        return $this->solve($expression, '#\(([^)^(]*)\)#', $expressionResolver);
    }

    private function solveAddition(string $expression): string
    {
        return $this->solve($expression, '#(\d+) \+ (\d+)#', fn($a, $b) => $a + $b);
    }

    private function solveMultiplication(string $expression): string
    {
        return $this->solve($expression, '#(\d+) \* (\d+)#', fn($a, $b) => $a * $b);
    }

    private function solveAddAndMulti(string $expression): string
    {
        return $this->solve($expression, '#(\d+) ([*+]) (\d+)#', fn($a, $op, $b) => $op === '*' ? $a * $b : $a + $b, 1);
    }

    private function solve(string $expression, string $pattern, callable $solver, int $limit = -1): string
    {
        $count = 0;
        do {
            $expression = preg_replace_callback(
                $pattern,
                fn($x) => $solver(...array_slice($x, 1)),
                $expression,
                $limit,
                $count
            );
        } while ($count > 0);
        return $expression;
    }
}
