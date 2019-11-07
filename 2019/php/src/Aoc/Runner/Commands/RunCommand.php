<?php


namespace Ppx17\Aoc2019\Aoc\Runner\Commands;


use Ppx17\Aoc2019\Aoc\Runner\DayInterface;
use Ppx17\Aoc2019\Aoc\Runner\DayLoader;
use Ppx17\Aoc2019\Aoc\Runner\DayRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{

    /**
     * @var DayRepository
     */
    private $days;

    public function __construct(DayRepository $repository)
    {
        parent::__construct(null);
        $this->days = $repository;
    }

    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Runs all registered days.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->days->each(function(DayInterface $day) use($output) {
            $output->writeln('Executing day '.$day->dayNumber());
        });
    }
}