#!/usr/bin/env ./node_modules/.bin/ts-node

import * as Days from "./days";
import {Cli, RunnerFactory} from "./aoc";

const runner = RunnerFactory.create(Days);

const cli = new Cli(runner);
cli.execute();
