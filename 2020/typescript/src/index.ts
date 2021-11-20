#!/usr/bin/env ./node_modules/.bin/ts-node

import * as Days from "./days";
import {Cli} from "./aoc/cli";
import {RunnerFactory} from "./aoc/runnerFactory";

const runner = RunnerFactory.create(Days);

const cli = new Cli(runner);
cli.execute();
