#!/usr/bin/env php
<?php

use Illuminate\Container\Container;
use Ppx17\Aoc2019\Aoc\Runner\Commands\RunCommand;
use Ppx17\Aoc2019\Aoc\Runner\DayInterface;
use Ppx17\Aoc2019\Aoc\Runner\DayLoader;
use Ppx17\Aoc2019\Aoc\Runner\DayRepository;
use Symfony\Component\Console\Application as ConsoleApplication;

require_once __DIR__ . '/src/vendor/autoload.php';

$app = new Container();

$app->singleton(DayRepository::class, function(Container $app) {
    $repository = new DayRepository();
    $loader = $app->make(DayLoader::class);
    $loader
        ->load(__DIR__ . '/src/Aoc/Days')
        ->each(function(DayInterface $day) use ($repository) {
            $repository->addDay($day);
        });

    return $repository;
});

/** @var ConsoleApplication $cli */
$cli = $app->build(ConsoleApplication::class);
$cli->add($app->build(RunCommand::class));

$cli->run();