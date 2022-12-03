#!/usr/bin/env ./node_modules/.bin/ts-node-script

import {Cli, RunnerFactory} from "./aoc";
import * as Days from "./days"

const runner = RunnerFactory.create(Days);

const cli = new Cli(runner);
cli.execute();
