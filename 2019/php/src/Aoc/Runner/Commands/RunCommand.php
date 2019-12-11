<?php


namespace Ppx17\Aoc2019\Aoc\Runner\Commands;


use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Runner\DayInterface;
use Ppx17\Aoc2019\Aoc\Runner\Result;
use Ppx17\Aoc2019\Aoc\Runner\Validator\ValidatedResult;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends AocCommand
{
    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Runs all registered days.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progressSection = $output->section();

        $progressSection->writeln('Running all days...');

        $currentSection = $output->section();

        $results = $this
            ->getDays()
            ->map(function (DayInterface $day) use ($currentSection) {
                $currentSection->writeln('Currently running day ' . $day->dayNumber() . '...');
                $result = $this->getRunner()->run($day);
                $currentSection->clear();
                return $result;
            })
            ->map(fn(Result $result) => $this->getValidator()->validate($result));

        $progressSection->clear();

        $this->printResultTable($output, $results);
    }

    private function printResultTable(OutputInterface $output, Collection $results)
    {
        $table = new Table($output);
        $table
            ->setHeaders([
                    [
                        'Day',
                        new TableCell('Timings', ['colspan' => 4]),
                        new TableCell('Results', ['colspan' => 2])
                    ],
                    ['Day', 'Init', 'Part 1', 'Part 2', 'Total', 'Part 1', 'Part 2']
                ]
            )
            ->setRows($results
                ->sortBy(fn(ValidatedResult $result) => $result->getResult()->getDay()->dayNumber())
                ->map(function (ValidatedResult $result) {
                    return [
                        $result->getResult()->getDay()->dayNumber(),
                        $this->formatTime($result->getResult()->getTimeSetup()),
                        $this->formatTime($result->getResult()->getTimePart1()),
                        $this->formatTime($result->getResult()->getTimePart2()),
                        $this->formatTime($result->getResult()->getTimeTotal()),
                        $this->resultCell($result->getPart1()),
                        $this->resultCell($result->getPart2()),
                    ];
                })
                ->toArray());
        $table->render();
    }
}
