<?php


namespace Ppx17\Aoc2015\Aoc\Runner\Commands;


use Ppx17\Aoc2015\Aoc\Runner\DayRepository;
use Ppx17\Aoc2015\Aoc\Runner\DayRunner;
use Ppx17\Aoc2015\Aoc\Runner\Validator\Part;
use Ppx17\Aoc2015\Aoc\Runner\Validator\ResultValidator;
use Symfony\Component\Console\Command\Command;

abstract class AocCommand extends Command
{
    private DayRepository $days;
    private DayRunner $runner;
    private ResultValidator $validator;

    public function __construct(DayRepository $repository, DayRunner $runner, ResultValidator $validator)
    {
        parent::__construct(null);
        $this->days = $repository;
        $this->runner = $runner;
        $this->validator = $validator;
    }

    protected function getValidator(): ResultValidator
    {
        return $this->validator;
    }

    protected function getRunner(): DayRunner
    {
        return $this->runner;
    }

    protected function getDays(): DayRepository
    {
        return $this->days;
    }

    protected function formatTime(float $ms): string
    {
        if ($ms < 1) {
            return sprintf('%.2f µs', $ms * 1000);
        }

        if ($ms > 1000) {
            return '<fg=red>' . sprintf('%.2f s', $ms) . '</>';
        }

        return sprintf('%.2f ms', $ms);
    }

    protected function unknown(string $message = ''): string
    {
        return '<fg=blue>? '.$message.'</>';
    }

    protected function success(string $message = ''): string
    {
        return '<fg=green>✔ '.$message.'</>';
    }

    protected function fail(string $message = ''): string
    {
        return '<fg=red>⨯ '.$message.'</>';
    }

    protected function resultCell(Part $part)
    {
        if (!$part->hasExpectation()) {
            return $this->unknown('Expectation not set.');
        }
        if ($part->isValid()) {
            return $this->success();
        }
        return $this->fail(sprintf('Expected "%s" but got "%s"',
            $part->getExpectation(), $part->getResult()));

    }
}
