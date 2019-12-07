<?php


namespace Ppx17\Aoc2019\Aoc\Runner\Commands;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends AocCommand
{
    private OutputInterface $output;

    protected function configure()
    {
        $this->setName('new')
            ->addArgument('day', InputArgument::REQUIRED)
            ->setDescription('Creates file for a day');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $dayNumber = $input->getArgument('day');
        $this->assertPath("../input/input-day{$dayNumber}.txt", '');
        $this->assertPath("../expected/day{$dayNumber}.txt", "Part 1: \nPart 2: \n");
        $this->assertPath("./src/Aoc/Days/Day{$dayNumber}.php", $this->template($dayNumber));

    }

    protected function assertPath(string $path, string $content): void
    {
        if (!file_exists($path)) {
            $this->output->writeln('Creating ' . basename($path));
            file_put_contents($path, $content);
            chown($path, 1000);
            chgrp($path, 1000);
        } else {
            $this->output->writeln('Already found ' . basename($path));
        }
    }

    private function template(int $day)
    {
        return "<?php


namespace Ppx17\\Aoc2019\\Aoc\\Days;


class Day{$day} extends AbstractDay
{
    public function dayNumber(): int
    {
        return {$day};
    }

    public function part1(): string
    {
        return '';
    }

    public function part2(): string
    {
        return '';
    }
}
";
    }
}