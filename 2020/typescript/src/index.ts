#!/usr/bin/env ./node_modules/.bin/ts-node

import {Runner} from "./aoc/runner";
import * as Days from "./days";
import {Cli} from "./aoc/cli";

const runner = new Runner();

for (const day in Days) {
    if (typeof Days[day] === "function") {
        runner.register(new Days[day]());
    }
}

const cli = new Cli(runner);
cli.execute();
