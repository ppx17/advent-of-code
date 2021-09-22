#!/usr/bin/env ./node_modules/.bin/ts-node

import yargs from 'yargs'
import {hideBin} from 'yargs/helpers'
import {Runner} from "./runner";
import * as Days from "./days";
import {Renderer} from "./renderer";

const runner = new Runner();

for(const day in Days) {
    runner.register(new Days[day]());
}

yargs(hideBin(process.argv))
    .command(['run', 'all'], 'Run all days', () => {
    }, (argv) => console.log(Renderer.multiple(runner.all())))
    .command('day <day>', 'Run single day', (yargs) => {
        yargs.positional('day', {
            type: 'number',
            demand: true,
            describe: 'the day to run'
        })
    }, (argv) => {
        const result = runner.day(argv.day as number);

        console.log(Renderer.single(result))

    })
    .demandCommand(1)
    .argv