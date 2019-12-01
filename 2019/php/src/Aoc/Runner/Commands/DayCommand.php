<?php


namespace Ppx17\Aoc2019\Aoc\Runner\Commands;


use Ppx17\Aoc2019\Aoc\Runner\Result;
use Ppx17\Aoc2019\Aoc\Runner\Validator\Part;
use Ppx17\Aoc2019\Aoc\Runner\Validator\ValidatedResult;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DayCommand extends AocCommand
{
    protected function configure()
    {
        $this->setName('day')
            ->addArgument('day', InputArgument::REQUIRED)
            ->setDescription('Runs a single day');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $day = $this->getDays()->getDay($input->getArgument('day'));
        if (is_null($day)) {
            $output->writeln('<error>Day not found.</error>');
        }

        $progressSection = $output->section();
        $progressSection->writeln('Running day ' . $day->dayNumber() . '...');
        $result = $this->getRunner()->run($day, $progressSection);
        $progressSection->clear();

        $validationResult = $this->getValidator()->validate($result);

        $this->writeResultsTable($output, $validationResult);
        $this->writeTimingTable($output, $result);
    }

    private function writeResultsTable(OutputInterface $output, ValidatedResult $validation)
    {
        $table = new Table($output);
        $table->setHeaders([
            [new TableCell('Results', ['colspan' => 3])],
        ]);
        $table->addRows([
            ['Part 1', $validation->getResult()->getPart1(), $this->resultCell($validation->getPart1())],
            ['Part 2', $validation->getResult()->getPart2(), $this->resultCell($validation->getPart2())],
        ]);
        $table->render();
    }

    private function writeTimingTable(OutputInterface $output, Result $result)
    {
        $table = new Table($output);
        $table->setHeaders(['Phase', 'Time']);
        $table->addRows([
            ['Setup', $this->formatTime($result->getTimeSetup())],
            ['Part 1', $this->formatTime($result->getTimePart1())],
            ['Part 2', $this->formatTime($result->getTimePart2())],
            new TableSeparator(),
            ['Total', $this->formatTime($result->getTimeTotal())]
        ]);
        $table->render();
    }
}