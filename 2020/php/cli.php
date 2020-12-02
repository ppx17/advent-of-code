#!/usr/bin/env php
<?php

use Illuminate\Container\Container;
use Ppx17\Aoc2020\Aoc\Runner\Commands\DayCommand;
use Ppx17\Aoc2020\Aoc\Runner\Commands\NewCommand;
use Ppx17\Aoc2020\Aoc\Runner\Commands\RunCommand;
use Ppx17\Aoc2020\Aoc\Runner\DayInterface;
use Ppx17\Aoc2020\Aoc\Runner\DayLoader;
use Ppx17\Aoc2020\Aoc\Runner\DayRepository;
use Ppx17\Aoc2020\Aoc\Runner\Validator\ResultValidator;
use Symfony\Component\Console\Application as ConsoleApplication;

require_once __DIR__ . '/src/vendor/autoload.php';

$app = new Container();

$app->singleton(DayRepository::class, function (Container $app) {
    $repository = new DayRepository();
    $loader = $app->make(DayLoader::class);
    $loader
        ->load(
            __DIR__ . '/src/Aoc/Days',
            __DIR__ . '/../input'
        )
        ->each(function (DayInterface $day) use ($repository) {
            $repository->addDay($day);
        });

    return $repository;
});

$app->singleton(ResultValidator::class, function (Container $app) {
    return new ResultValidator(__DIR__ . '/../expected');
});

/** @var ConsoleApplication $cli */
$cli = $app->build(ConsoleApplication::class);
$cli->add($app->build(RunCommand::class));
$cli->add($app->build(DayCommand::class));
$cli->add($app->build(NewCommand::class));

$cli->run();